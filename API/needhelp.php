<?php

	include("config.php");

	$recipient = $_POST['recipient'];
	$recip_phone = $_POST['recip_phone'];

	$message = $_POST['message'];
	$helpers = $_POST['helpers'];

	$i_sql = "
INSERT INTO help_requests (requestor, requestor_phone, problem ) 
VALUES ('%s', '%s', '%s')";

	$i_sql = sprintf($i_sql, $recipient, $recip_phone, $message);
	mysql_query($i_sql);

	$request_id = mysql_insert_id();

	/*
	$self_url = sprintf('http://%s/%s/contact_help.php', 
						$_SERVER['HTTP_HOST'], 'RHOK-GS/API');

	*/

	$T = new TwilioRestClient($AccountSid, $AuthToken);

	$vars = array('From'=> $FromNumber, 
		'Body' => "$recipient needs help with ${message} reply 'help ${request_id}' to offer assistance");


	foreach ($helpers as $the_helper) {
		$vars['To'] = $the_helper;

		$resp = $T->request("Accounts/${AccountSid}/SMS/Messages", "POST", $vars);

		$SID = $resp->ResponseXml->SMSMessage->Sid;

		$i_sql = "
INSERT INTO helper_responses (request_id, helper_phone ) 
VALUES (%d, '%s')";

		$i_sql = sprintf($i_sql, $request_id, $the_helper);
		mysql_query($i_sql);

	}
?>

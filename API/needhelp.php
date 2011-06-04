<?php

	include("config.php");

	$recipient = $_POST['recipient'];
	$recip_phone = $_POST['recip_phone'];

	$message = $_POST['message'];
	$helpers = $_POST['helpers'];
	//printf("%s Needs help %s\n", $recipient, $recip_phone);

	$self_url = sprintf('http://%s/%s/contact_help.php', 
						$_SERVER['HTTP_HOST'], 'RHOK-GS/API');

	$T = new TwilioRestClient($AccountSid, $AuthToken);

	$vars = array('From'=> '(408) 645-7465', 
		'Body' => "$recipient needs help with ${message} reply 'yes ${recipient}' to offer assistance");


	foreach ($helpers as $the_helper) {
		$vars['To'] = $the_helper;

		echo "Contacting ${the_helper}\n";
		$resp = $T->request("Accounts/${AccountSid}/SMS/Messages", "POST", $vars);

		$SID = $resp->ResponseXml->SMSMessage->Sid;
		$fp = fopen("sid_log.txt", "a");
		fwrite($fp, "${the_helper},$recipient,$recip_phone\n");
		fclose($fp);

	}
?>

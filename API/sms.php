<?php

	include("config.php");

	ob_start();

	$body = $_POST['Body'];
	$from = $_POST['From'];

	$T = new TwilioRestClient($AccountSid, $AuthToken, "https://api.twilio.com/2010-04-01");

	$matches = array();
	
	if (preg_match('/help ([0-9]+)/i', $body, $matches)) {
		$who = $matches[1];
		$response = "Ok, go help ${who}";
		
		$q_sql = "SELECT requestor_phone FROM help_requests WHERE id = ${who}";
		$q = mysql_query($q_sql);

		$row = mysql_fetch_array($q, MYSQL_ASSOC);

		$callback = sprintf("http://%s/%s?call=%s", $_SERVER['HTTP_HOST'], 
			"/RHOK-GS/API/voice.php", urlencode($from));

		$vars = array('From' => $FromNumber, 'To' => $row['requestor_phone'], 
			'Url' => $callback);

		$resp = $T->request("Accounts/${AccountSid}/Calls", "POST", $vars);

		echo $resp->ResponseText;

	} else {
		$response = "";
	}

	$cont = ob_get_contents();
	mail("andrewmoorewatson@gmail.com","RHOK", $cont);
	ob_end_clean();
	
	header("Content-type: text/xml");

?>
<Response>
	<Sms><?php echo $response; ?></Sms>
</Response>

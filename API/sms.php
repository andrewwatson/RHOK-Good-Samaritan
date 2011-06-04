<?php

	include("config.php");

	ob_start();
	var_dump($_POST);
	$cont = ob_get_contents();

	mail("andrewmoorewatson@gmail.com","RHOK", $cont);
	ob_end_clean();

	$body = $_POST['Body'];
	$from = $_POST['From'];

	$T = new TwilioRestClient($AccountSid, $AuthToken);

	$matches = array();
	if (preg_match('/help ([a-z])/', $body, $matches)) {
		$who = $matches[0];
	}
	
	header("Content-type: text/xml");

?>
<Response>
</Response>

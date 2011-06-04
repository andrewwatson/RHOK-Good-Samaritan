<?php

	header("Content-type: text/xml\n");
?>
<Response>
	<Dial><Number><?php echo $_GET['call']; ?></Number></Dial>
</Response>

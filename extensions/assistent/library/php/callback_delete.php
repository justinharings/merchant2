<?php
if(!isset($_SESSION))
{
	session_start();
}

define("_LANGUAGE_PACK", "nl");

require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/classes/motherboard.php");
	
$mb = new motherboard();

$query = sprintf(
	"	DELETE FROM		assistent_callback
		WHERE			assistent_callback.callbackID = %d",
	intval($_POST['callbackID'])
);
$mb->query($query);
?>

<script type="text/javascript">
	parent.document.location.href = '/assistent/';
</script>
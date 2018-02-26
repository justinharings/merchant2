<?php
if(!isset($_SESSION))
{
	session_start();
}

define("_LANGUAGE_PACK", "nl");

require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/classes/motherboard.php");
	
$mb = new motherboard();
$mb->_runFunction("reviews", "delete", array(1, $_POST));
?>

<script type="text/javascript">
	parent.document.location.href = '/assistent/';
</script>
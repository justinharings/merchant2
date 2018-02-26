<?php
if(!isset($_SESSION))
{
	session_start();
}

define("_LANGUAGE_PACK", "nl");

require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/classes/motherboard.php");
	
$mb = new motherboard();

$workorder = $mb->_runFunction("workorders", "loadWorkorder", array($_POST['workorderID']));
$mb->_runFunction("workorders", "delete", array($workorder['workorderID'], $workorder['customerID']));
?>

<script type="text/javascript">
	parent.document.location.href = '/assistent/';
</script>
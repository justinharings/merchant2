<?php
if(!isset($_SESSION))
{
	session_start();
}

define("_LANGUAGE_PACK", "nl");

require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/classes/motherboard.php");
	
$_POST['status'] = 1;
	
$mb = new motherboard();

$workorder = $mb->_runFunction("workorders", "loadWorkorder", array($_POST['workorderID']));

$mb->_runFunction("workorders", "saveWorkorderStatus", array(1, $_POST));
$mb->_runFunction("mailserver", "sendAllSMS", array(1, 1, $workorder['phone_number'], $workorder['workorderID'], 0));

$query = sprintf(
	"	UPDATE			workorders
		SET				workorders.expiration_date = NOW()
		WHERE			workorders.workorderID = %d",
	$_POST['workorderID']
);
$mb->query($query);
?>

<script type="text/javascript">
	parent.document.location.href = '/assistent/';
</script>
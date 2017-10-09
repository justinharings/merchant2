<?php
if(!isset($_SESSION))
{
	session_start();
}

for($i = 1; $i <= 4; $i++)
{
	$_SESSION['invoice_rules'][$i-1]['key'] = $_POST['key_' . $i];
	$_SESSION['invoice_rules'][$i-1]['value'] = $_POST['value_' . $i];
}

header("location: /extensions/point_of_sale/modules/popup_close.php");
?>
<?php
if(!isset($_SESSION))
{
	session_start();
}

$_SESSION['shipment'] = $_POST['shipmentID'];

header("location: /extensions/point_of_sale/modules/popup_close.php");
?>
<?php
if(!isset($_SESSION))
{
	session_start();
}

unset($_SESSION['cart']);
unset($_SESSION['customer']);
unset($_SESSION['shipment']);
unset($_SESSION['key_number']);
unset($_SESSION['invoice_rules']);
unset($_SESSION['employeeID']);
unset($_SESSION['grand_total']);
unset($_SESSION['payments']);
unset($_SESSION['payed']);
unset($_SESSION['loaded_payed']);
unset($_SESSION['statusID']);
unset($_SESSION['orderID']);
unset($_SESSION['terminal']);

if(isset($popup) && $popup == true)
{
	header("location: /extensions/point_of_sale/modules/popup_close.php");
	exit;
}

header("location: /pos/modules/register/");
?>
<?php
if(!isset($_SESSION))
{
	session_start();
}
	
unset($_SESSION['grand_total']);
unset($_SESSION['payments']);
unset($_SESSION['payed']);
unset($_SESSION['statusID']);
unset($_SESSION['orderID']);

header("location: /extensions/point_of_sale/modules/popup_payment.php");
?>
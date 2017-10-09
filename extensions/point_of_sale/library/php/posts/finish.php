<?php
// Start session

if(!isset($_SESSION))
{
	session_start();
}

define("_LANGUAGE_PACK", "nl");

/*
**	Functions are added here. Used for quick access to all
**	of the extended special functions, all the files
**	are added to the core here.
*/

require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/functions/arrays.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/functions/floats.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/functions/text.php");



/*
**	Classes are included here. We use a motherboard
**	class that is able to construct all the classes
**	and is able to run this class his function.
*/

require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/classes/motherboard.php");

$mb = new motherboard();



// CART ITEMS
// Array must be:
// array[KEY]['productID']
// array[KEY]['price']
// array[KEY]['name']
// array[KEY]['quantity']

$_cart = array();
$num = 0;

foreach($_SESSION['cart'] AS $cart)
{
	$_cart[$num]['productID'] = $cart['code'];
	$_cart[$num]['price'] = $cart['price'];
	$_cart[$num]['name'] = $cart['name'];
	$_cart[$num]['quantity'] = $cart['quantity'];
	
	$num++;
}



// CUSTOMER
// Variable must hold customerID.

$_customer = (isset($_SESSION['customer']) ? $_SESSION['customer'] : 0);



// PAYMENTS
// Array must be:
// array[KEY]['paymentID']
// array[KEY]['amount']

$_payments = array();
$num = 0;

$_has_cash = 0;

foreach($_SESSION['payments'] AS $payment)
{
	$_payments[$num]['paymentID'] = $payment['paymentID'];
	$_payments[$num]['amount'] = $payment['amount'];
	
	if($payment['cash'])
	{
		$_has_cash = 1;
		
		if($_SESSION['payed'] > $_SESSION['grand_total'])
		{
			$_payments[$num]['amount'] = $_payments[$num]['amount'] - ($_SESSION['payed']-$_SESSION['grand_total']);
		}
	}
	
	$num++;
}

if($_has_cash == 0 && ($_SESSION['payed'] > $_SESSION['grand_total']))
{
	$cashID = $mb->_runFunction("payment_methods", "loadCashID", array($_SESSION['merchantID']));
	
	if($cashID)
	{
		$_payments[$num]['paymentID'] = $cashID;
		$_payments[$num]['amount'] = ($_SESSION['grand_total'] - $_SESSION['payed']);
	}
}



// STATUS
// Variable must hold statusID.

$default_status = $mb->_runFunction("pos", "loadGeneralSettings", array($_SESSION['merchantID']));
$_status = (isset($_SESSION['statusID']) ? $_SESSION['statusID'] : $default_status['statusID']);



// SHIPMENT
// Variable must hold shipmentID.

$default_shipment = $mb->_runFunction("pos", "loadGeneralSettings", array($_SESSION['merchantID']));
$_shipment = (isset($_SESSION['shipment']) ? $_SESSION['shipment'] : $default_shipment['shipmentID']);



// EMPLOYEE
// Variable must hold employeeID.

$_employee = (isset($_SESSION['employeeID']) ? $_SESSION['employeeID'] : 0);



// SET OPTIONAL ORDER
// Variable must hold orderID

$_orderID = (isset($_SESSION['orderID']) ? $_SESSION['orderID'] : 0);



//print "<pre>" . print_r($_cart, true) . "</pre><br/><br/>";
//print $_customer . "<br/><br/>";
//print "<pre>" . print_r($_payments, true) . "</pre><br/><br/>";
//print $_status . "<br/><br/>";
//print $_shipment . "<br/><br/>";
//print $_employee . "<br/><br/>";
//print $_orderID . "<br/><br/>";



// Start the process inside the order class.
$orderID = $mb->_runFunction("orders", "runOrder", array($_SESSION['merchantID'], $_cart, $_customer, $_payments, $_status, $_employee, $_shipment, $_orderID));
$_SESSION['last_order'] = $orderID;



// Reset all the sessions
$popup = true;
require_once(__DIR__ . "/cart_reset.php");
?>
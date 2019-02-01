<?php
if(!isset($_SESSION))
{
	session_start();
}


unset($_SESSION['grand_total']);
unset($_SESSION['payments']);
unset($_SESSION['payed']);
unset($_SESSION['statusID']);


if(!isset($_SESSION['cart']))
{
	$_SESSION['cart'] = array();
}


$cart = $_SESSION['cart'];
$nmbr = count($cart);


if(isset($_GET['barcode']))
{
	$_POST['barcode'] = $_GET['barcode'];
}

if(isset($_GET['qty']))
{
	$_POST['qty'] = $_GET['qty'];
}

if(isset($_GET['key']))
{
	$_POST['key'] = $_GET['key'];
}

$price = "";

if(isset($_POST['qty']) && $_POST['qty'] > 0)
{
	foreach($_SESSION['cart'] AS $key => $value)
	{
		if($key == $_POST['key'])
		{
			$_SESSION['cart'][$key]['quantity'] = $_POST['barcode'];
		}
	}
		
	$focus = $_POST['key'];
}
else
{
	$_POST['article_code'] = intval($_POST['barcode']);
	
	require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/classes/motherboard.php");
	
	$mb = new motherboard();
	$product = $mb->_runFunction("categories", "returnProductBasedOnArticleCode", array($_SESSION['merchantID'], $_POST));
	
	if(!$product['productID'])
	{
		$product = $mb->_runFunction("categories", "returnProductBasedOnBarcode", array($_SESSION['merchantID'], $_POST));
	}
	
	if($product['productID'])
	{
		$cart[$nmbr]['code'] = $product['productID'];
		$cart[$nmbr]['price'] = $product['price'];
		$cart[$nmbr]['name'] = $product['name'];
		$cart[$nmbr]['quantity'] = 1;
		
		if($product['name_change'] == 1)
		{
			$price = "name/";
		}
		else if($product['price'] == 0)
		{
			$price = "price/";
		}
		
		$_SESSION['cart'] = $cart;
	}
	
	$focus = "last";
}

header("location: /pos/modules/register/focus/" . $focus . "/" . $price);
?>
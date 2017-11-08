<?php
if(!isset($_SESSION))
{
	session_start();
}

foreach($_SESSION['cart'] AS $key => $value)
{
	if($key == $_POST['key'])
	{
		if($_POST['type'] == "price")
		{
			$_SESSION['cart'][$key]['price'] = str_replace(",", ".", $_POST['value']);
		}
		else
		{
			$price = $_SESSION['cart'][$key]['price'];
			$discount = ($price / 100) * str_replace(",", ".", $_POST['value']);
			
			$_SESSION['cart'][$key]['price'] = $price - $discount;
		}
	}
}

header("location: /extensions/point_of_sale/modules/popup_close.php?force=register/focus/" . $_POST['key'] . "/");
?>
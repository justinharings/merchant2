<?php
if(!isset($_SESSION))
{
	session_start();
}

foreach($_SESSION['cart'] AS $key => $value)
{
	if($key == $_GET['key'])
	{
		unset($_SESSION['cart'][$key]);
	}
}

header("location: /pos/modules/register");
?>
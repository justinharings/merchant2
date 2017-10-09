<?php
if(!isset($_SESSION))
{
	session_start();
}

unset($_SESSION['shipment']);

header("location: /pos/modules/register/");
?>
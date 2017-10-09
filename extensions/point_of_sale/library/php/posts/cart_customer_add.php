<?php
if(!isset($_SESSION))
{
	session_start();
}

$_SESSION['customer'] = $_GET['key'];

header("location: /pos/modules/register/");
?>
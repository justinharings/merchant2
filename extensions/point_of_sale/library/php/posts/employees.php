<?php
if(!isset($_SESSION))
{
	session_start();
}

$_SESSION['employeeID'] = $_GET['employeeID'];

header("location: /pos/modules/register/");
?>
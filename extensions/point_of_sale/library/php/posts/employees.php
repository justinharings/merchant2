<?php
if(!isset($_SESSION))
{
	session_start();
}

$_SESSION['employeeID'] = $_GET['employeeID'];

unset($_SESSION['print_button_order']);
unset($_SESSION['print_button_workorder']);

header("location: /pos/modules/register/");
?>
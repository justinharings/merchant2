<?php
// Start session

if(!isset($_SESSION))
{
	session_start();
}
	
	
	
/*
**	Classes are included here. We use a motherboard
**	class that is able to construct all the classes
**	and is able to run this class his function.
*/

require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/classes/motherboard.php");

$mb = new motherboard();



/*
**	Check if the user given exists within
**	the Mechant database. If not, return
**	to the login page. If it does set the
**	authorization session and continue.
*/

if(isset($_POST['login_code']))
{
	$sessions = $mb->_runFunction("authorization", "validateDataPos", array($_POST['login_code']));
	
	foreach($sessions AS $key => $value)
	{
		$_SESSION[$key] = $value;
	}
}

header("location: /pos/modules/register/");
?>
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

if(isset($_POST['username']) && isset($_POST['password']))
{
	$sessions = $mb->_runFunction("authorization", "validateData", array($_POST['username'], $_POST['password']));
	
	foreach($sessions AS $key => $value)
	{
		$_SESSION[$key] = $value;
	}
}

$page = "";

if(file_exists($_SERVER['DOCUMENT_ROOT'] . "/modules" . substr($sessions['start_page'], 0, (strlen($sessions['start_page'])-1)) . ".php"))
{
	$page = $sessions['start_page'];
}

header("location: /" . $_SESSION['_LANGUAGE_PACK'] . "/modules" . $page);
?>
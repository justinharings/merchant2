<?php
// Start session

if(!isset($_SESSION))
{
	session_start();
}
	
	
	
/*
**	Remove all the known sessions in order for the
**	check to see we've logout!
*/

foreach($_SESSION AS $key => $value)
{
	unset($_SESSION[$key]);
}

print true;
?>
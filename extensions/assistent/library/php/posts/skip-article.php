<?php
// Start session

if(!isset($_SESSION))
{
	session_start();
}


/*
**	POS Only operates in the NL language pack.
*/

define("_LANGUAGE_PACK", "nl");



/*
**	Tell the classes and functions if the development
**	mode is activated or not. This will allow the classes
**	to display a user-friendly message or the real 
**	PHP exception for the developer.
*/

$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

define("_DEVELOPMENT_ENVIRONMENT", (strpos($actual_link, "dev.") !== false ? true : false));
$_SESSION['_DEVELOPMENT_ENVIRONMENT'] = _DEVELOPMENT_ENVIRONMENT;



/*
**	Functions are added here. Used for quick access to all
**	of the extended special functions, all the files
**	are added to the core here.
*/

require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/functions/arrays.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/functions/floats.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/functions/text.php");



/*
**	Classes are included here. We use a motherboard
**	class that is able to construct all the classes
**	and is able to run this class his function.
*/

require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/classes/motherboard.php");

$mb = new motherboard();
	
$query = sprintf(
	"	INSERT INTO		assistent
		SET				assistent.barcode = '%s'",
	$_GET['barcode']
);
$mb->query($uqery);

header("location: /assistent/modules/add-new/");
?>
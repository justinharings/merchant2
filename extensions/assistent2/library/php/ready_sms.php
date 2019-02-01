<?php
if(!isset($_SESSION))
{
	session_start();
}



define("_LANGUAGE_PACK", "nl");

$_SESSION['merchantID'] = 1;



$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

define("_DEVELOPMENT_ENVIRONMENT", (strpos($actual_link, "dev.") !== false ? true : false));
$_SESSION['_DEVELOPMENT_ENVIRONMENT'] = _DEVELOPMENT_ENVIRONMENT;



require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/functions/arrays.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/functions/floats.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/functions/text.php");

require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/classes/motherboard.php");



$mb = new motherboard();


if(isset($_GET['orderID']))
{
	//$_GET['phone'] = "0611625660";
	
	$content = "Uw nieuwe fiets staat klaar conform afspraak. We zien u graag in de winkel!";
	$mb->_runFunction("mailserver", "sendSms", array(1, $_GET['phone'], $content, $_GET['customerID'], 0, $_GET['orderID']));
}

header("location: /assistent2/?module=ready&sms=true&orderID=" . $_GET['orderID']);
?>
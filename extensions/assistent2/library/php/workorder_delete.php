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


if(isset($_GET['workorderID']))
{
	if($_GET['customerID'] > 0)
	{
		$query = sprintf(
			"	UPDATE		workorders
				SET			workorders.removed = 1
				WHERE		workorders.workorderID = %d",
			intval($_GET['workorderID'])
		);
		$mb->query($query);
	}
	else
	{
		$query = sprintf(
			"	DELETE FROM		workorders
				WHERE			workorders.workorderID = %d",
			intval($_GET['workorderID'])
		);
		$mb->query($query);
	}
}

header("location: /assistent2/");
?>
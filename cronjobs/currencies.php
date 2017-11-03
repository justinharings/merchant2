<?php
define("_DEVELOPMENT_ENVIRONMENT", true);
	
require_once("/var/www/vhosts/justinharings.nl/merchant.justinharings.nl/library/php/classes/database.php");
$db = new database();



$content = file_get_contents("https://www.wisselkoers.nl/britse_pond");

$content = explode("Laatste koers</span><span>", $content);
$content = $content[1];

$content = explode("</span>", $content);
$content = $content[0];

$content = str_replace(",", ".", $content);
$content = number_format($content, 2);



$query = sprintf(
	"	DELETE FROM		currencies
		WHERE			currencies.currency = 'GBP'"
);
$db->query($query);

$query = sprintf(
	"	INSERT INTO		currencies
		SET				currencies.currency = 'GBP',
						currencies.target = '%.2f'",
	$content
);
$db->query($query);
?>
<?php
define("_DEVELOPMENT_ENVIRONMENT", true);
	
require_once("/var/www/vhosts/justinharings.nl/merchant.justinharings.nl/library/php/classes/database.php");
$db = new database();



/** GBP **/

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




/** USD **/

$content = file_get_contents("https://www.wisselkoers.nl/dollar");

$content = explode("Laatste koers</span><span>", $content);
$content = $content[1];

$content = explode("</span>", $content);
$content = $content[0];

$content = str_replace(",", ".", $content);
$content = number_format($content, 2);


$query = sprintf(
	"	DELETE FROM		currencies
		WHERE			currencies.currency = 'USD'"
);
$db->query($query);

$query = sprintf(
	"	INSERT INTO		currencies
		SET				currencies.currency = 'USD',
						currencies.target = '%.2f'",
	$content
);
$db->query($query);




/** DKK **/

$content = file_get_contents("https://www.wisselkoers.nl/deense_kroon");

$content = explode("Laatste koers</span><span>", $content);
$content = $content[1];

$content = explode("</span>", $content);
$content = $content[0];

$content = str_replace(",", ".", $content);
$content = number_format($content, 2);


$query = sprintf(
	"	DELETE FROM		currencies
		WHERE			currencies.currency = 'DKK'"
);
$db->query($query);

$query = sprintf(
	"	INSERT INTO		currencies
		SET				currencies.currency = 'DKK',
						currencies.target = '%.2f'",
	$content
);
$db->query($query);




/** NOK **/

$content = file_get_contents("https://www.wisselkoers.nl/noorse_kroon");

$content = explode("Laatste koers</span><span>", $content);
$content = $content[1];

$content = explode("</span>", $content);
$content = $content[0];

$content = str_replace(",", ".", $content);
$content = number_format($content, 2);


$query = sprintf(
	"	DELETE FROM		currencies
		WHERE			currencies.currency = 'NOK'"
);
$db->query($query);

$query = sprintf(
	"	INSERT INTO		currencies
		SET				currencies.currency = 'NOK',
						currencies.target = '%.2f'",
	$content
);
$db->query($query);




/** CHF **/

$content = file_get_contents("https://www.wisselkoers.nl/zwitserse_frank");

$content = explode("Laatste koers</span><span>", $content);
$content = $content[1];

$content = explode("</span>", $content);
$content = $content[0];

$content = str_replace(",", ".", $content);
$content = number_format($content, 2);


$query = sprintf(
	"	DELETE FROM		currencies
		WHERE			currencies.currency = 'CHF'"
);
$db->query($query);

$query = sprintf(
	"	INSERT INTO		currencies
		SET				currencies.currency = 'CHF',
						currencies.target = '%.2f'",
	$content
);
$db->query($query);




/** AUD **/

$content = file_get_contents("https://www.wisselkoers.nl/australische_dollar");

$content = explode("Laatste koers</span><span>", $content);
$content = $content[1];

$content = explode("</span>", $content);
$content = $content[0];

$content = str_replace(",", ".", $content);
$content = number_format($content, 2);


$query = sprintf(
	"	DELETE FROM		currencies
		WHERE			currencies.currency = 'AUD'"
);
$db->query($query);

$query = sprintf(
	"	INSERT INTO		currencies
		SET				currencies.currency = 'AUD',
						currencies.target = '%.2f'",
	$content
);
$db->query($query);




/** CAD **/

$content = file_get_contents("https://www.wisselkoers.nl/canadese_dollar");

$content = explode("Laatste koers</span><span>", $content);
$content = $content[1];

$content = explode("</span>", $content);
$content = $content[0];

$content = str_replace(",", ".", $content);
$content = number_format($content, 2);


$query = sprintf(
	"	DELETE FROM		currencies
		WHERE			currencies.currency = 'CAD'"
);
$db->query($query);

$query = sprintf(
	"	INSERT INTO		currencies
		SET				currencies.currency = 'CAD',
						currencies.target = '%.2f'",
	$content
);
$db->query($query);




/** SEK **/

$content = file_get_contents("https://www.wisselkoers.nl/zweedse_kroon");

$content = explode("Laatste koers</span><span>", $content);
$content = $content[1];

$content = explode("</span>", $content);
$content = $content[0];

$content = str_replace(",", ".", $content);
$content = number_format($content, 2);


$query = sprintf(
	"	DELETE FROM		currencies
		WHERE			currencies.currency = 'SEK'"
);
$db->query($query);

$query = sprintf(
	"	INSERT INTO		currencies
		SET				currencies.currency = 'SEK',
						currencies.target = '%.2f'",
	$content
);
$db->query($query);




/** BRL **/

$content = file_get_contents("https://www.wisselkoers.nl/braziliaanse_reaal");

$content = explode("Laatste koers</span><span>", $content);
$content = $content[1];

$content = explode("</span>", $content);
$content = $content[0];

$content = str_replace(",", ".", $content);
$content = number_format($content, 2);


$query = sprintf(
	"	DELETE FROM		currencies
		WHERE			currencies.currency = 'BRL'"
);
$db->query($query);

$query = sprintf(
	"	INSERT INTO		currencies
		SET				currencies.currency = 'BRL',
						currencies.target = '%.2f'",
	$content
);
$db->query($query);
?>
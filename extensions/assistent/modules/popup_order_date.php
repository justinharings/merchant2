<?php
// Start session

if(!isset($_SESSION))
{
	session_start();
}

define("_LANGUAGE_PACK", "nl");

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
?>

<!DOCTYPE html>
<html lang="nl">
	<head>
		<link rel="stylesheet" type="text/css" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" />
		<link rel="stylesheet" type="text/css" href="/library/css/motherboard.minified.css" />
		<link rel="stylesheet" type="text/css" href="/library/css/pos.css" />

		<script type="text/javascript" src="//code.jquery.com/jquery-latest.js"></script>
		<script type="text/javascript" src="/library/js/motherboard.minified.js"></script>
		<script type="text/javascript" src="/library/js/pos.js"></script>
	</head>

	<body class="popup">
		<form method="post" action="/extensions/assistent/library/php/pickup_date.php">
			<input type="text" name="value" id="value" value="" class="width-100-percent margin large text-center popup-keyboard-output remove-default date-field" holder="Afhaaldatum" holder-eg="Type de datum zonder strepen." />
			<input type="hidden" name="orderID" id="orderID" value="<?= $_GET['orderID'] ?>" />
			
			<div class="button first popup-keyboard">
				<div class="pos-button keyboard">1</div>
			</div>
			
			<div class="button popup-keyboard">
				<div class="pos-button keyboard">2</div>
			</div>
			
			<div class="button popup-keyboard">
				<div class="pos-button keyboard">3</div>
			</div>
			
			<div class="button popup-keyboard">
				<div class="pos-button keyboard">4</div>
			</div>
			
			<div class="button first popup-keyboard">
				<div class="pos-button keyboard">5</div>
			</div>
			
			<div class="button popup-keyboard">
				<div class="pos-button keyboard">6</div>
			</div>
			
			<div class="button popup-keyboard">
				<div class="pos-button keyboard">7</div>
			</div>
			
			<div class="button popup-keyboard">
				<div class="pos-button keyboard">8</div>
			</div>
			
			<div class="button first popup-keyboard">
				<div class="pos-button keyboard">9</div>
			</div>
			
			<div class="button popup-keyboard">
				<div class="pos-button keyboard">0</div>
			</div>
			
			<div class="button popup-keyboard">
				<div class="pos-button"></div>
			</div>
			
			<div class="button popup-keyboard">
				<div class="pos-button fa fa-backward keyboard"></div>
			</div>
			
			<input type="submit" name="continue" id="continue" value="Datum opslaan" class="width-100-percent red" />
		</form>
	</body>
</html>	
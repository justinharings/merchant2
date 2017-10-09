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
$doc = $mb->_runFunction("workorders", "loadDocumentation", array($_GET['key']));
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
		<form method="post" action="/extensions/workshop/library/php/posts/documentation.php">
			<input type="hidden" name="documentID" id="documentID" value="<?= isset($_GET['key']) ? $_GET['key'] : 0 ?>" />
			<input type="hidden" name="employeeID" id="employeeID" value="<?= $_SESSION['employeeID'] ?>" />
			
			<input type="text" name="name" id="name" value="<?= isset($_GET['key']) ? $doc['name'] : "" ?>" class="width-100-percent margin" holder="Titel" holder-eg="Een duidelijke en passende referentie." />
			<textarea name="content" id="content" class="width-100-percent double-margin"><?= isset($_GET['key']) ? $doc['content'] : "" ?></textarea>
			
			<input type="submit" name="save" id="save" value="Gegevens opslaan" class="width-100-percent red" />
		</form>
	</body>
</html>	
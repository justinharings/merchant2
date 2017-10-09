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
		<form id="barcode-form" method="post" action="/extensions/point_of_sale/library/php/posts/customer_card.php">
			<img src="/library/media/barcode.png" />
			<input type="text" name="customer_code" id="customer_code" value="" class="width-100-percent text-center" />
			<input type="hidden" name="customerID" id="customerID" value="<?= intval($_GET['key']) ?>" />
		</form>
		
		<script type="text/javascript">
			$(document).ready(
				function($)
				{
					$("#customer_code").focus();
				}
			);
		</script>
	</body>
</html>	
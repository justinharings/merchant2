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
		<input type="button" name="close_today" id="close_today" value="Vandaag afslaan" class="width-100-percent red margin" />
		<input type="button" name="close_yesterday" id="close_yesterday" value="Gisteren afslaan" class="width-100-percent" />
		
		<script type="text/javascript">
			$(document).ready(
				function($)
				{
					$("#close_today").on("click",
						function()
						{
							window.open("/extensions/point_of_sale/modules/close_register.php?period=today");
						}
					);
					
					$("#close_yesterday").on("click",
						function()
						{
							window.open("/extensions/point_of_sale/modules/close_register.php?period=yesterday");
						}
					);
				}
			);
		</script>
	</body>
</html>	
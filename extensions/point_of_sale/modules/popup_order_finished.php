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
$data = $mb->_runFunction("pos", "loadPaymentMethods", array($_SESSION['merchantID']));
?>

<!DOCTYPE html>
<html lang="nl">
	<head>
		<link rel="stylesheet" type="text/css" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" />
		<link rel="stylesheet" type="text/css" href="/library/css/motherboard.minified.css" />
		<link rel="stylesheet" type="text/css" href="/library/css/pos.css" />

		<script type="text/javascript" src="//code.jquery.com/jquery-latest.js"></script>
		
		<script type="text/javascript" src="/library/js/dashboard.minified.js"></script>
		<script type="text/javascript" src="/library/js/datepicker.minified.js"></script>
		<script type="text/javascript" src="/library/js/emails.minified.js"></script>
		<script type="text/javascript" src="/library/js/framework.minified.js"></script>
		<script type="text/javascript" src="/library/js/input.minified.js"></script>
		<script type="text/javascript" src="/library/js/multiselect.minified.js"></script>
		<script type="text/javascript" src="/library/js/notes.minified.js"></script>
		<script type="text/javascript" src="/library/js/sms.minified.js"></script>
		
		<script type="text/javascript" src="/library/js/pos.js"></script>
	</head>

	<body class="popup">
		<form method="post" action="/extensions/point_of_sale/library/php/posts/cart_name.php">
			<h1>
				Bestelling afgerond.
				
				<small>
					<?php
					if($_SESSION['payed'] > $_SESSION['grand_total'])
					{
						?>
						<br/>
						De klant krijgt &euro;&nbsp;<?= _frontend_float($_SESSION['payed'] - $_SESSION['grand_total']) ?> terug.
						<?php
					}
					else
					{
						?>
						<br/>
						De klant krijgt geen geld terug.
						<?php
					}
					?>
				</small>
			</h1>
			
			<div class="order-finished first" target="eject">
				<span class="fa fa-eject"></span>
			</div>
			
			<div class="order-finished" target="finish">
				<span class="fa fa-check"></span>
			</div>
		</form>
	</body>
</html>	
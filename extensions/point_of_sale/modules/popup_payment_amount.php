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
$payment = $mb->_runFunction("payment_methods", "load", array($_GET['paymentID']));

if(isset($_GET['grand_total']))
{
	$_GET['grand_total'] = str_replace(",", ".", $_GET['grand_total']);
	
	if($_GET['grand_total'] <= 0)
	{
		$_GET['grand_total'] = $_SESSION['grand_total'];
	}
	
	$to_pay = number_format($_GET['grand_total'], 2);
	
	if(isset($_SESSION['payed']))
	{
		$to_pay = $to_pay - $_SESSION['payed'];
	}
}
else
{
	die("Er is een fout opgetreden.");
}
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
		
		<script type="text/javascript">
			$(document).ready(
				function()
				{
					var posted = false;
					
					$("#form").submit(
						function(event)
						{
							if(posted == false && $(".cash").val() == 0)
							{
								event.preventDefault();
								
								var form = $(this);
								
								form.hide();
								
								$("div.popup-container", parent.document)
									.css("width", "600px")
									.css("height", "388px")
									.css("margin-top", "-194px")
									.css("margin-left", "-300px");
									
								$("div.atm-image").css("display", "table");
								
								setTimeout(
									function() 
									{ 
										posted = true;
										form.submit();
									}, 7000
								);
							}
						}
					);
				}
			);
		</script>
	</head>

	<body class="popup">
		<div class="atm-image">
			<img src="/library/media/pinautomaat.gif" />
		</div>
		
		<form id="form" name="form" method="post" action="/extensions/point_of_sale/library/php/posts/payment.php">
			<h1>Betaling: <?= $payment['name'] ?></h1>
			<input type="text" name="to_pay" id="to_pay" value="<?= _frontend_float($to_pay) ?>" class="width-100-percent margin large text-center" holder="Totaal te betalen" holder-eg="Het (restant) totaalbedrag." />
			<input type="text" name="value" id="value" value="<?= _frontend_float($to_pay) ?>" class="width-100-percent margin large text-center popup-keyboard-output remove-default" holder="Klant betaald" holder-eg="Type het bedrag dat je ontvangt." />
			
			<input type="hidden" name="paymentID" id="paymentID" value="<?= $_GET['paymentID'] ?>" />
			<input type="hidden" name="cash" id="cash" value="<?= ($_GET['target'] == "cash" ? 1 : 0) ?>" />
			
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
				<div class="pos-button">,</div>
			</div>
			
			<div class="button popup-keyboard">
				<div class="pos-button fa fa-backward keyboard"></div>
			</div>
			
			<input type="submit" name="continue" id="continue" value="Doorgaan" class="width-100-percent red" />
		</form>
	</body>
</html>	
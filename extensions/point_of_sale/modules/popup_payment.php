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
		<script type="text/javascript" src="/library/js/motherboard.minified.js"></script>
		<script type="text/javascript" src="/library/js/pos.js"></script>
	</head>

	<body class="popup">
		<form method="post" action="/extensions/point_of_sale/library/php/posts/cart_name.php">
			<h1>
				<?php
				if(isset($_SESSION['payed']))
				{
					print "Nog &euro;&nbsp;" . _frontend_float($_SESSION['grand_total']-$_SESSION['payed']) . " te betalen.";
				}
				else
				{
					print "Hoe wilt u afrekenen?";
				}
				?>
			</h1>
			
			<?php
			$nm = 0;
			foreach($data AS $method)
			{
				?>
				
				<div class="payment-method <?= $nm == 0 ? "first" : "" ?>" paymentID="<?= $method['paymentID'] ?>" target="<?= $method['cash'] ? "cash" : "card" ?>">
					<?= $method['name'] ?>
				</div>
				
				<?php
				$nm++;
				
				if($nm == 2)
				{
					$nm = 0;
				}
			}			
			?>		
			
			<div class="payment-method <?= !isset($_SESSION['customer']) ? "inactive" : "" ?> <?= $nm == 0 ? "first" : "" ?>" paymentID="0">
				Geen betaling
			</div>
		</form>
	</body>
</html>	
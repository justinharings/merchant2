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
		<form id="barcode-form" method="post" action="/extensions/point_of_sale/library/php/posts/customer_card.php">
			<img src="/library/media/barcode.png" />
			
			<select name="cardID" id="cardID" class="width-100-percent text-center margin" style="text-align-last: center;">
				<option value="0">Geen klantenkaart pakket instellen</option>
				
				<?php
				$data = $mb->_runFunction("customers", "viewCards", array($_SESSION['merchantID'], (isset($_GET['search_string']) ? trim($_GET['search_string'], "/") : ""), "cards.name", "0,50"));
				
				if($mb->num_rows($data))
				{
					foreach($data AS $value)
					{
						?>
						<option value="<?= $value['cardID'] ?>"><?= $value['name'] ?></option>
						<?php
					}
				}
				?>
			</select>
			
			<input type="text" name="customer_code" id="customer_code" value="" class="width-100-percent text-center" placeholder="Klantenkaart barcode" />
			
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
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
		<form method="post" action="/extensions/point_of_sale/library/php/posts/cart_shipment_add.php">
			<select multiple="multiple" name="shipmentID" id="shipmentID" class="width-100-percent no-multiselect" style="height: 103px;">
				<?php
				$data_shipments = $mb->_runFunction("shipment_methods", "view", array($_SESSION['merchantID'], "", "shipment_methods.name", "0,50"));
				$num = 0;
				
				foreach($data_shipments AS $method)
				{
					?>
					<option <?= $num == 0 ? "selected=\"selected\"" : "" ?> value="<?= $method['shipmentID'] ?>"><?= $method['name'] ?></option>
					<?php
						
					$num++;
				}
				?>
			</select>
			
			<div class="select-control up">
				<span class="fa fa-caret-up"></span>
			</div>
			
			<div class="select-control down">
				<span class="fa fa-caret-down"></span>
			</div>
			
			<input type="submit" name="select" id="select" value="Optie selecteren" class="width-100-percent red margin" />
		</form>
	</body>
</html>	
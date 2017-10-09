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
		<form method="post" action="/extensions/point_of_sale/library/php/posts/cart_invoice_rules.php">
			<table width="100%">
				<tbody>
					<?php
					for($i = 1; $i <= 4; $i++)
					{
						?>
						<tr>
							<td width="220">
								<select name="key_<?= $i ?>" id="key_<?= $i ?>" class="width-200 margin" <?= $i == 1 ? "holder=\"Titel\"" : "" ?>>
									<option value=""></option>
									<option <?= $_SESSION['invoice_rules'][$i-1]['key'] == "Aanbetaling" ? "selected=\"selected\"" : "" ?> value="Aanbetaling">Aanbetaling</option>
									<option <?= $_SESSION['invoice_rules'][$i-1]['key'] == "Accessoires" ? "selected=\"selected\"" : "" ?> value="Accessoires">Accessoires</option>
									<option <?= $_SESSION['invoice_rules'][$i-1]['key'] == "Afhaalmoment" ? "selected=\"selected\"" : "" ?> value="Afhaalmoment">Afhaalmoment</option>
									<option <?= $_SESSION['invoice_rules'][$i-1]['key'] == "Betaling" ? "selected=\"selected\"" : "" ?> value="Betaling">Betaling</option>
									<option <?= $_SESSION['invoice_rules'][$i-1]['key'] == "Extra informatie" ? "selected=\"selected\"" : "" ?> value="Extra informatie">Extra informatie</option>
									<option <?= $_SESSION['invoice_rules'][$i-1]['key'] == "Extra's" ? "selected=\"selected\"" : "" ?> value="Extra's">Extra's</option>
									<option <?= $_SESSION['invoice_rules'][$i-1]['key'] == "Framenummer" ? "selected=\"selected\"" : "" ?> value="Framenummer">Framenummer</option>
									<option <?= $_SESSION['invoice_rules'][$i-1]['key'] == "Onderdelen" ? "selected=\"selected\"" : "" ?> value="Onderdelen">Onderdelen</option>
									<option <?= $_SESSION['invoice_rules'][$i-1]['key'] == "Opmerkingen" ? "selected=\"selected\"" : "" ?> value="Opmerkingen">Opmerkingen</option>
									<option <?= $_SESSION['invoice_rules'][$i-1]['key'] == "Overigen" ? "selected=\"selected\"" : "" ?> value="Overigen">Overigen</option>
									<option <?= $_SESSION['invoice_rules'][$i-1]['key'] == "Sleutelnummer" ? "selected=\"selected\"" : "" ?> value="Sleutelnummer">Sleutelnummer</option>
									<option <?= $_SESSION['invoice_rules'][$i-1]['key'] == "Ten behoeve van" ? "selected=\"selected\"" : "" ?> value="Ten behoeve van">Ten behoeve van</option>
									<option <?= $_SESSION['invoice_rules'][$i-1]['key'] == "Voorwaarden" ? "selected=\"selected\"" : "" ?> value="Voorwaarden">Voorwaarden</option>
								</select>
							</td>
							<td><input type="text" name="value_<?= $i ?>" id="value_<?= $i ?>" value="<?= $_SESSION['invoice_rules'][$i-1]['value'] ?>" class="width-300 margin" <?= $i == 1 ? "holder=\"Waarde\"" : "" ?> /></td>
						</tr>
						<?php
					}
					?>
				</tbody>
			</table>
			<br/>
			<input type="submit" name="select" id="select" value="Gegevens opslaan" class="width-100-percent red margin" />
		</form>
	</body>
</html>	
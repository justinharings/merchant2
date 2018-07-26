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
$card = $mb->_runFunction("workorders", "loadWorkorderCard", array($_GET['workorderID']));

$data = array();
$num = 1;

foreach($card AS $key => $value)
{
	if($value['description'] == "Montage kosten")
	{
		$data[10]['price'] = $value['price'];
		continue;
	}
	
	$data[$num]['description'] = $value['description'];
	$data[$num]['price'] = $value['price'];
	
	$num++;
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
	</head>

	<body class="popup">
		<form method="post" action="/extensions/workshop/library/php/posts/card.php">
			<input type="hidden" name="workorderID" id="workorderID" value="<?= isset($_GET['workorderID']) ? $_GET['workorderID'] : 0 ?>" />
			
			<table width="100%">
				<tbody>
					<?php
					for($i = 1; $i <= 10; $i++)
					{
						$value = (isset($data[$i]) ? $data[$i]['description'] : "");
						$price = (isset($data[$i]) ? $data[$i]['price'] : "");
						?>
						<tr>
							<td width="320">
								<input <?= $i == 10 ? "readonly=\"readonly\"" : "" ?> type="text" name="value_<?= $i ?>" id="value_<?= $i ?>" value="<?= $i == 10 ? "Montage kosten" : $value ?>" class="width-300 margin" <?= $i == 1 ? "holder=\"Omschrijving\"" : "" ?> />
							</td>
							
							<td>
								<input type="text" name="price_<?= $i ?>" id="price_<?= $i ?>" value="<?= $price ?>" class="width-100 margin" <?= $i == 1 ? "holder=\"Prijs\"" : "" ?> icon="fa-euro" />
							</td>
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
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

if(isset($_GET['key']))
{
	$data = $mb->_runFunction("customers", "load", array($_GET['key']));
}
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
		<form method="post" action="/extensions/point_of_sale/library/php/posts/customer.php">
			<input type="hidden" name="customerID" id="customerID" value="<?= isset($_GET['key']) ? intval($_GET['key']) : 0 ?>" />
			<input type="hidden" name="customer_code" id="customer_code" value="<?= isset($_GET['key']) ? $data['customer_code'] : "" ?>" />
			
			<input type="text" name="name" id="name" value="<?= isset($_GET['key']) ? $data['name'] : "" ?>" class="width-300 margin" holder="<?= $mb->_translateReturn("forms", "form-customers-name") ?>" validation-required="true" validation-type="text" />
			<input type="text" name="company" id="company" value="<?= isset($_GET['key']) ? $data['company'] : "" ?>" class="width-300 double-margin" holder="<?= $mb->_translateReturn("forms", "form-customers-company") ?>" holder-eg="<?= $mb->_translateReturn("forms", "form-customers-company-eg") ?>" />
			
			<input type="text" name="address" id="address" value="<?= isset($_GET['key']) ? $data['address'] : "" ?>" class="width-300 margin" holder="<?= $mb->_translateReturn("forms", "form-customers-address") ?>" holder-eg="<?= $mb->_translateReturn("forms", "form-customers-address-eg") ?>" validation-required="true" validation-type="text" />
			<input type="text" name="zip_code" id="zip_code" value="<?= isset($_GET['key']) ? $data['zip_code'] : "" ?>" class="width-100 margin" holder="<?= $mb->_translateReturn("forms", "form-customers-zipcode") ?>" validation-required="true" validation-type="text" />
			<input type="text" name="city" id="city" value="<?= isset($_GET['key']) ? $data['city'] : "" ?>" class="width-300 margin" holder="<?= $mb->_translateReturn("forms", "form-customers-city") ?>" validation-required="true" validation-type="text" />
			
			<select name="country" id="country" class="width-300 double-margin" holder="<?= $mb->_translateReturn("forms", "form-customers-country") ?>">
				<?php
				$_countries = $mb->_allCountries();
				
				foreach($_countries AS $value)
				{
					?>
					<option <?= (isset($_GET['key']) && $data['country'] == $value) || (!isset($_GET['key']) && $value == "Netherlands") ? "selected=\"selected\"" : "" ?> value="<?= $value ?>"><?= $value ?></option>
					<?php
				}
				?>
			</select>
			
			<input type="text" name="phone" id="phone" value="<?= isset($_GET['key']) ? $data['phone'] : "" ?>" class="width-300 margin" holder="<?= $mb->_translateReturn("forms", "form-customers-phone") ?>" />
			<input type="text" name="mobile_phone" id="mobile_phone" value="<?= isset($_GET['key']) ? $data['mobile_phone'] : "" ?>" class="width-300 margin" holder="<?= $mb->_translateReturn("forms", "form-customers-mobile-phone") ?>" />
			<input type="text" name="email_address" id="email_address" value="<?= isset($_GET['key']) ? $data['email_address'] : "" ?>" class="width-300 double-margin" holder="<?= $mb->_translateReturn("forms", "form-customers-email") ?>" validation-type="email" />
			
			<input type="submit" name="select" id="select" value="Gegevens opslaan" class="width-100-percent red margin" />
		</form>
	</body>
</html>	
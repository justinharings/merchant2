<?php
$mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "SET_BM", 1));

if(isset($_GET['dataID']))
{
	$data = $mb->_runFunction("payment_methods", "load", array($_GET['dataID']));
}
?>

<ul class="breadcrumbs">
	<li>Merchant</li>
	<li><?= $mb->_translateReturn("menu", "settings") ?></li>
	<li><?= $mb->_translateReturn("menu", "payment-methods") ?></li>
	<li><?= (isset($_GET['dataID']) ? $data['name'] : $mb->_translateReturn("forms", "add")) ?></li>
</ul>

<form method="post" id="form" action="/library/php/posts/instellingen/verzendmethoden.php">
	<input type="hidden" name="paymentID" id="paymentID" value="<?= isset($_GET['dataID']) ? $_GET['dataID'] : 0 ?>" />
	<input type="hidden" name="returnURL" id="returnURL" value="<?= "/" . _LANGUAGE_PACK . "/modules/" . $_GET['module'] . "/" . $_GET['file'] ?>" />
	
	<div class="simple-form">
		<div class="form-header">
			<h1><?= (isset($_GET['dataID']) ? $mb->_translateReturn("forms", "edit", array($data['name'])) : $mb->_translateReturn("forms", "add-new-title")) ?></h1>
			
			<input type="button" name="return" id="return" value="<?= $mb->_translateReturn("forms", "button-cancel") ?>" class="show-load" />
			
			<?php
			if(isset($_GET['dataID']))
			{
				?>
				<input type="button" name="delete-item" id="delete-item" value="<?= $mb->_translateReturn("forms", "button-delete") ?>" class="white show-load" />
				<?php
			}
			?>
			
			<input type="submit" name="save" id="save" value="<?= $mb->_translateReturn("forms", "button-save") ?>" class="red show-load validate-form" />
		</div>
		
		<div class="form-content">
			<div class="content-header">
				<span class="fa fa-pencil-square-o"></span>
				<?= $mb->_translateReturn("forms", "legend-general") ?>
			</div>
			
			<input type="text" name="name" id="name" value="<?= isset($_GET['dataID']) ? $data['name'] : "" ?>" class="width-300 double-margin" holder="<?= $mb->_translateReturn("forms", "form-payments-name") ?>" holder-eg="<?= $mb->_translateReturn("forms", "form-payments-name-eg") ?>" validation-required="true" validation-type="text" />
			<input type="text" name="description" id="description" value="<?= isset($_GET['dataID']) ? $data['description'] : "" ?>" class="width-300 margin" holder="<?= $mb->_translateReturn("forms", "form-payments-description") ?>" holder-eg="<?= $mb->_translateReturn("forms", "form-payments-description-eg") ?>" validation-required="true" validation-type="text" />
			
			<?php
			$_lang = $mb->_allLanguages();
			
			if($mb->num_rows($_lang))
			{
				?>
				<div class="languages width-300">
					<span class="fa fa-chevron-circle-down"></span>
					
					<?php
					foreach($_lang AS $value)
					{
						?>
						<fieldset>
							<legend><?= $value['language'] ?></legend>
							<input type="text" name="<?= $value['code'] ?>_description" id="<?= $value['code'] ?>_description" value="<?= isset($_GET['dataID']) ? $data[$value['code'] . '_description'] : "" ?>" class="width-100-percent" validation-required="true" validation-type="text" icon="fa-globe" />
						</fieldset>
						<?php
					}
					?>
				</div>
				<?php
			}
			?>
			
			<input type="text" name="maximum_amount" id="maximum_amount" value="<?= isset($_GET['dataID']) ? $data['maximum_amount'] : "" ?>" class="width-150 double-margin" holder="<?= $mb->_translateReturn("forms", "form-payments-maximum-amount") ?>" validation-required="false" validation-type="int" icon="fa-euro" question="Indien deze betaalmethode in de webshop uitgeschakeld moet worden wanneer er een bepaald bedrag bereikt is, vul dan dat maximale bedrag hier in." />
			
			<input type="checkbox" <?= isset($_GET['dataID']) && $data['webshop'] == 1 ? "checked=\"checked\"" : "" ?> name="webshop" id="webshop" value="1" class="margin" holder="<?= $mb->_translateReturn("forms", "form-payments-webshop") ?>" />
			<input type="checkbox" <?= isset($_GET['dataID']) && $data['pos'] == 1 ? "checked=\"checked\"" : "" ?> name="pos" id="pos" value="1" class="margin" holder="<?= $mb->_translateReturn("forms", "form-payments-pos") ?>" />
			
			<!--
			<input type="checkbox" <?= isset($_GET['dataID']) && $data['cash'] == 1 ? "checked=\"checked\"" : "" ?> name="cash" id="cash" value="1" holder="<?= $mb->_translateReturn("forms", "form-payments-cash") ?>" question="" />
			-->
		</div>	
			
		<div class="form-content">
			<div class="content-header">
				<span class="fa fa-pencil-square-o"></span>
				<?= $mb->_translateReturn("forms", "legend-payment-module") ?>
			</div>
			
			<select name="module" id="module" class="width-300 margin" holder="<?= $mb->_translateReturn("forms", "form-payments-module") ?>" holder-eg="<?= $mb->_translateReturn("forms", "form-payments-module-eg") ?>" validation-required="false" validation-type="text" question="Kies hier een eventuele betalingsmodule uit. Deze module wordt actief wanneer de klant hiervoor gekozen heeft in de webshop en gaat afrekenen. De betaling verschijnt onder het tabblad 'betalingen' bij de order." />
				<option value=""></option>
				
				<?php
				foreach($mb->_runFunction("payment_methods", "payment_modules", array()) AS $key => $value)
				{
					?>
					<option <?= isset($_GET['dataID']) && $data['module'] == $value['folder'] ? "selected=\"selected\"" : "" ?> value="<?= $value['folder'] ?>" activate="api_key_1,api_key_2"><?= $value['name'] ?></option>
					<?php
				}
				?>
			</select>
			
			<input type="text" name="api_key_1" id="api_key_1" value="<?= isset($_GET['dataID']) ? $data['api_key_1'] : "" ?>" class="select-option width-300 margin" holder="<?= $mb->_translateReturn("forms", "form-payments-api-key-1") ?>" validation-required="false" validation-type="text" />
			<input type="text" name="api_key_2" id="api_key_2" value="<?= isset($_GET['dataID']) ? $data['api_key_2'] : "" ?>" class="select-option width-300" holder="<?= $mb->_translateReturn("forms", "form-payments-api-key-2") ?>" validation-required="false" validation-type="text" />
		</div>
	</div>
</form>
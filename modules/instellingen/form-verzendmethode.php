<?php
$mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "SET_VB", 1));

if(isset($_GET['dataID']))
{
	$data = $mb->_runFunction("shipment_methods", "load", array($_GET['dataID']));
}
?>

<ul class="breadcrumbs">
	<li>Merchant</li>
	<li><?= $mb->_translateReturn("menu", "settings") ?></li>
	<li><?= $mb->_translateReturn("menu", "shipping-methods") ?></li>
	<li><?= (isset($_GET['dataID']) ? $data['name'] : $mb->_translateReturn("forms", "add")) ?></li>
</ul>

<form method="post" id="form" action="/library/php/posts/instellingen/verzendingen.php">
	<input type="hidden" name="shipmentID" id="shipmentID" value="<?= isset($_GET['dataID']) ? $_GET['dataID'] : 0 ?>" />
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
			
			<input type="text" name="name" id="name" value="<?= isset($_GET['dataID']) ? $data['name'] : "" ?>" class="width-300 margin" holder="<?= $mb->_translateReturn("forms", "form-shipment-name") ?>" holder-eg="<?= $mb->_translateReturn("forms", "form-shipment-name-eg") ?>" validation-required="true" validation-type="text" />
			
			<div class="languages width-300">
				<span class="fa fa-chevron-circle-down"></span>
							
				<?php
				$_lang = $mb->_allLanguages();
				
				foreach($_lang AS $value)
				{
					?>
					<fieldset>
						<legend><?= $value['language'] ?></legend>
						<input type="text" name="<?= $value['code'] ?>_name" id="<?= $value['code'] ?>_name" value="<?= isset($_GET['dataID']) ? $data[$value['code'] . '_name'] : "" ?>" class="width-100-percent" validation-required="true" validation-type="text" icon="fa-globe" />
					</fieldset>
					<?php
				}
				?>
			</div>
			
			<input type="text" name="courier" id="courier" value="<?= isset($_GET['dataID']) ? $data['courier'] : "" ?>" class="width-300 margin" holder="<?= $mb->_translateReturn("forms", "form-shipment-courier") ?>" validation-type="text" />
			<input type="text" name="maximum" id="maximum" value="<?= isset($_GET['dataID']) ? $data['maximum'] : "" ?>" class="width-200" holder="<?= $mb->_translateReturn("forms", "form-shipment-max") ?>" validation-required="true" validation-type="int" />
		</div>
		
		<div class="form-content">
			<div class="content-header">
				<span class="fa fa-pencil-square-o"></span>
				<?= $mb->_translateReturn("forms", "legend-prices-taxes") ?>
			</div>
			
			<input type="text" name="price" id="price" value="<?= isset($_GET['dataID']) ? _frontend_float($data['price']) : "" ?>" class="width-100 margin" icon="fa-euro" holder="<?= $mb->_translateReturn("forms", "form-shipment-price") ?>" validation-required="true" validation-type="int" />
			
			<div class="languages width-150">
				<span class="fa fa-chevron-circle-down"></span>
				
				<?php
				$_lang = $mb->_allLanguages();
				
				foreach($_lang AS $value)
				{
					?>
					<fieldset>
						<legend><?= $value['language'] ?></legend>
						<input type="text" name="<?= $value['code'] ?>_price" id="<?= $value['code'] ?>_price" value="<?= isset($_GET['dataID']) ? _frontend_float($data[$value['code'] . '_price']) : "" ?>" class="width-100-percent" validation-required="true" validation-type="int" icon="fa-globe" />
					</fieldset>
					<?php
				}
				?>
			</div>
			
			<select name="taxesID" id="taxesID" class="width-300" holder="<?= $mb->_translateReturn("forms", "form-shipment-taxes") ?>">
				<?php
				$_taxes = $mb->_runFunction("taxes", "view", array($_SESSION['merchantID'], "", "taxes.percentage", 50));
				
				foreach($_taxes AS $key => $value)
				{
					?>
					<option <?= isset($_GET['dataID']) && $data['taxesID'] == $value['taxesID'] ? "selected=\"selected\"" : "" ?> value="<?= $value['taxesID'] ?>"><?= $value['name'] . " (" . _frontend_float($value['percentage']) ." %)" ?></option>
					<?php
				}
				?>
			</select>
		</div>
			
		<div class="form-content">
			<div class="content-header">
				<span class="fa fa-pencil-square-o"></span>
				<?= $mb->_translateReturn("forms", "legend-options") ?>
			</div>
			
			<input type="checkbox" <?= isset($_GET['dataID']) && $data['free_choice'] ? "checked=\"checked\"" : "" ?> name="free_choice" id="free_choice" value="1" class="margin" holder="<?= $mb->_translateReturn("forms", "form-shipment-free-choice") ?>" />
			<input type="checkbox" <?= isset($_GET['dataID']) && $data['combine'] ? "checked=\"checked\"" : "" ?> name="combine" id="combine" value="1" class="margin" holder="<?= $mb->_translateReturn("forms", "form-shipment-combine") ?>" />
			<input type="checkbox" <?= isset($_GET['dataID']) && $data['pay_once'] ? "checked=\"checked\"" : "" ?> name="pay_once" id="pay_once" value="1" holder="<?= $mb->_translateReturn("forms", "form-shipment-pay") ?>" />
		</div>
	</div>
</form>
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
							<input type="text" name="<?= $value['code'] ?>_name" id="<?= $value['code'] ?>_name" value="<?= isset($_GET['dataID']) ? $data[$value['code'] . '_name'] : "" ?>" class="width-100-percent" validation-required="true" validation-type="text" icon="fa-globe" />
						</fieldset>
						<?php
					}
					?>
				</div>
				<?php
			}
			?>
			
			<input type="text" name="courier" id="courier" value="<?= isset($_GET['dataID']) ? $data['courier'] : "" ?>" class="width-300 margin" holder="<?= $mb->_translateReturn("forms", "form-shipment-courier") ?>" validation-type="text" question="Dit bedrijf is ter administratie. Het verschijnt onder de orders en blijft daar ook staan, ook wanneer je hem hier verandert. Verandert het bedrijf door de jaren heen dan kunt u bij oude orders nog terugvinden met welke transporteur u de bestelling verzonden heeft." />
			<input type="text" name="maximum" id="maximum" value="<?= isset($_GET['dataID']) ? $data['maximum'] : "" ?>" class="width-200" holder="<?= $mb->_translateReturn("forms", "form-shipment-max") ?>" validation-required="true" validation-type="int" question="Dit is het maximale aantal wat hierop kan openstaan. Heeft uw verzend- of afhaalmethode een limiet aan wat die aan kan? Dan kunt u hier dat limiet invullen." />
		</div>
		
		<div class="form-content">
			<div class="content-header">
				<span class="fa fa-pencil-square-o"></span>
				<?= $mb->_translateReturn("forms", "legend-prices-taxes") ?>
			</div>
			
			<input type="text" name="price" id="price" value="<?= isset($_GET['dataID']) ? _frontend_float($data['price']) : "" ?>" class="width-100 double-margin" icon="fa-euro" holder="<?= $mb->_translateReturn("forms", "form-shipment-price") ?>" validation-required="true" validation-type="int" />
			
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
				<?= $mb->_translateReturn("forms", "legend-prices-divergent") ?>
			</div>
		
			<table class="form-table">
				<thead>
					<tr>
						<td width="400"><?= $mb->_translateReturn("forms", "form-shipment-table-language") ?></td>
						<td><?= $mb->_translateReturn("forms", "form-shipment-table-price") ?></td>
						<td width="1"><span class="add-row fa fa-plus-circle"></span></td>
					</tr>
				</thead>
				
				<tbody>
					<?php
					foreach($data['fees'] AS $value)
					{
						?>
						<tr>
							<td><?= $value['country'] ?></td>
							<td>&euro;&nbsp;<?= _frontend_float($value['fee']) ?></td>
							<td>
								<span class="remove-row fa fa-remove" post="/library/php/posts/instellingen/verwijder_verzendmethoden_fee.php?feeID=<?= $value['feeID'] ?>&returnURL=<?= "/" . _LANGUAGE_PACK . "/modules/" . $_GET['module'] . "/" . $_GET['file'] . "/" . $_GET['form'] . "/" . $_GET['dataID'] ?>"></span>
							</td>
						</tr>
						<?php
					}
					?>
					
					<tr class="new-row">
						<td>
							<select name="export_fee_country[]" id="export_fee_country_+" class="width-200">
								<option value="Overige landen">Overige landen</option>
								<?php
								$_lang = $mb->_allCountries();
								
								foreach($_lang AS $value)
								{
									if($value == "Netherlands")
									{
										continue;
									}
									
									?>
									<option value="<?= $value ?>"><?= $value ?></option>
									<?php
								}
								?>
							</select>
						</td>
						
						<td><input type="text" name="export_fee_price[]" id="export_fee_price_+" value="" class="width-100" validation-required="true" validation-type="int" icon="fa-euro" /></td>
						<td class="searched-p-productID">&nbsp;</td>
					</tr>
				</tbody>
			</table>
		</div>
		
		<div class="form-content">
			<div class="content-header">
				<span class="fa fa-pencil-square-o"></span>
				<?= $mb->_translateReturn("forms", "legend-options") ?>
			</div>
			
			<input type="checkbox" <?= isset($_GET['dataID']) && $data['free_choice'] ? "checked=\"checked\"" : "" ?> name="free_choice" id="free_choice" value="1" class="margin" holder="<?= $mb->_translateReturn("forms", "form-shipment-free-choice") ?>" question="De klant kan deze verzend- of afhaaloptie vrij kiezen in de webwinkel. Als dit uit staat dan wordt deze alleen actief wanneer hij ingevult is bij een product dat de klant heeft uitgekozen. Zie hiervoor het formulier van een product." />
			<input type="checkbox" <?= isset($_GET['dataID']) && $data['combine'] ? "checked=\"checked\"" : "" ?> name="combine" id="combine" value="1" class="margin" holder="<?= $mb->_translateReturn("forms", "form-shipment-combine") ?>" question="Indien dit een 'kleiner pakket' is wat kan worden samengevoegd met een groter pakket, zet dan dit vinkje aan. Koopt de klant iets kleins én iets groots? Dan voegt het systeem automatisch alle kleine producten (met dit vinkje aan) samen met de grote pakketten. Zo betaald de klant maar één keer." />
			<input type="checkbox" <?= isset($_GET['dataID']) && $data['pay_once'] ? "checked=\"checked\"" : "" ?> name="pay_once" id="pay_once" value="1" holder="<?= $mb->_translateReturn("forms", "form-shipment-pay") ?>" question="Koopt de klant meerdere producten met deze verzendmethode? Dan kunt u kiezen voor 'eenmalig afrekenen'. Zo betaald de klant maar eenmalig." />
		</div>
	</div>
</form>
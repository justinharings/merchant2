<?php
$mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "SET_MB", 1));

if(isset($_GET['dataID']))
{
	$data = $mb->_runFunction("stock", "loadLocation", array($_GET['dataID']));
}
?>

<ul class="breadcrumbs">
	<li>Merchant</li>
	<li><?= $mb->_translateReturn("menu", "stock") ?></li>
	<li><?= $mb->_translateReturn("menu", "manage-locations") ?></li>
	<li><?= (isset($_GET['dataID']) ? $data['name'] : $mb->_translateReturn("forms", "add")) ?></li>
</ul>

<form method="post" id="form" action="/library/php/posts/voorraad/locaties.php">
	<input type="hidden" name="locationID" id="locationID" value="<?= isset($_GET['dataID']) ? $_GET['dataID'] : 0 ?>" />
	<input type="hidden" name="returnURL" id="returnURL" value="<?= "/" . _LANGUAGE_PACK . "/modules/" . $_GET['module'] . "/" . $_GET['file'] ?>" />
	
	<div class="simple-form">
		<div class="form-header">
			<h1><?= (isset($_GET['dataID']) ? $mb->_translateReturn("forms", "edit", array($data['name'])) : $mb->_translateReturn("forms", "add-new-title")) ?></h1>
			
			<input type="button" name="return" id="return" value="<?= $mb->_translateReturn("forms", "button-cancel") ?>" class="show-load" />
			
			<?php
			if(isset($_GET['dataID']) && $data['stock'] == 0 && $data['webshop'] == 0)
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
			
			<input type="text" name="name" id="name" value="<?= isset($_GET['dataID']) ? $data['name'] : "" ?>" class="width-300 margin" holder="<?= $mb->_translateReturn("forms", "form-locations-name") ?>" holder-eg="<?= $mb->_translateReturn("forms", "form-locations-name-eg") ?>" validation-required="true" validation-type="text" />
			<input type="text" name="pos_card" id="pos_card" value="<?= isset($_GET['dataID']) ? $data['pos_card'] : "" ?>" class="width-150 double-margin" icon="fa-barcode" holder="<?= $mb->_translateReturn("forms", "form-locations-code") ?>" validation-required="true" validation-type="int" question="Scan hier uw Merchant kaart voor deze locatie. De Merchant kaart ontvangt u van ons wanneer u ons kassasysteem gaat gebruiken. Alleen met uw Merchant kaart kunt u inloggen op uw POS. Iedere locatie heeft een unieke pas met unieke barcode." />
			
			<input type="checkbox" <?= isset($_GET['dataID']) && $data['webshop'] == 1 ? "checked=\"checked\"" : "" ?> name="webshop" id="webshop" value="1" holder="<?= $mb->_translateReturn("forms", "form-locations-webshop") ?>" question="Wordt er in de webshop vanuit deze locatie verkocht? Zet deze vink dan op 'ja'. Alle webshop orders zullen worden afgehandeld via de voorraad van deze locatie." />
		</div>
	</div>
</form>
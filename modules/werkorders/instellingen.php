<?php
$mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "WOR", 1));
$data = $mb->_runFunction("workorders", "loadSettings", array($_SESSION['merchantID']));
?>

<ul class="breadcrumbs">
	<li>Merchant</li>
	<li>Werkorder instellingen</li>
</ul>

<form method="post" id="form" action="/library/php/posts/werkorders/instellingen.php">
	<input type="hidden" name="returnURL" id="returnURL" value="<?= "/" . _LANGUAGE_PACK . "/modules/" . $_GET['module'] . "/" . $_GET['file'] ?>" />
	
	<div class="simple-form">
		<div class="form-header">
			<h1>Werkorder instellingen</h1>
			
			<input type="button" name="return" id="return" value="<?= $mb->_translateReturn("forms", "button-cancel") ?>" class="show-load" />
			<input type="submit" name="save" id="save" value="<?= $mb->_translateReturn("forms", "button-save") ?>" class="red show-load validate-form" />
		</div>
		
		<div class="form-content">
			<div class="content-header">
				<span class="fa fa-pencil-square-o"></span>
				<?= $mb->_translateReturn("forms", "legend-manage-content") ?>
			</div>
			
			<textarea name="receipt_content" id="receipt_content" class="width-400" holder="<?= $mb->_translateReturn("forms", "form-workorders-receipt-content") ?>" holder-eg="<?= $mb->_translateReturn("forms", "form-workorders-receipt-content-eg") ?>" question="Dit is de tekst die u kunt weergeven op het afhaalbewijs van uw werkorders. U kunt barcodes weergeven op deze afhaalbewijzen zodat u de werkorder gemakkelijk kunt terugvinden wanneer de klant u het afhaalbewijs geeft."><?= (isset($data['receipt_content']) ? $data['receipt_content'] : "") ?></textarea>
		</div>
		
		<div class="form-content">
			<div class="content-header">
				<span class="fa fa-pencil-square-o"></span>
				<?= $mb->_translateReturn("forms", "legend-options") ?>
			</div>
			
			<input type="checkbox" <?= isset($data['radio']) && $data['radio'] == 1 ? "checked=\"checked\"" : "" ?> name="radio" id="radio" value="1" class="margin" holder="<?= $mb->_translateReturn("forms", "form-workorders-radio") ?>" question="Wilt u dat er in het werkorder scherm de muziek optie beschikbaar is? Zet dan dit vinkje aan." />
			<input type="checkbox" <?= isset($data['unique_identifier']) && $data['unique_identifier'] == 1 ? "checked=\"checked\"" : "" ?> name="unique_identifier" id="unique_identifier" value="1" holder="<?= $mb->_translateReturn("forms", "form-workorders-unique-identifier") ?>" question="Gebruikt u sleutelnummers of andere identificatie nummers? Dan kunt u er voor zorgen dat het systeem erop controleert dat u unieke nummers gebruikt ter indentificatie." />
		</div>
	</div>
</form>
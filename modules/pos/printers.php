<?php
$mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "POS_PI", 1));
$data = $mb->_runFunction("pos", "loadPrinterSettings", array($_SESSION['merchantID']));
?>

<ul class="breadcrumbs">
	<li>Merchant</li>
	<li><?= $mb->_translateReturn("menu", "pos") ?></li>
	<li><?= $mb->_translateReturn("menu", "pos-printers") ?></li>
</ul>

<form method="post" id="form" action="/library/php/posts/pos/printers.php">
	<input type="hidden" name="returnURL" id="returnURL" value="<?= "/" . _LANGUAGE_PACK . "/modules/" . $_GET['module'] . "/" . $_GET['file'] ?>" />
	
	<div class="simple-form">
		<div class="form-header">
			<h1><?= $mb->_translateReturn("menu", "pos-printers") ?></h1>
			
			<input type="button" name="return" id="return" value="<?= $mb->_translateReturn("forms", "button-cancel") ?>" class="show-load" />
			<input type="submit" name="save" id="save" value="<?= $mb->_translateReturn("forms", "button-save") ?>" class="red show-load validate-form" />
		</div>
		
		<div class="form-content">
			<div class="content-header">
				<span class="fa fa-pencil-square-o"></span>
				<?= $mb->_translateReturn("forms", "legend-google-cloud-print") ?>
			</div>
			
			<input type="text" name="google_cloud_api_key" id="google_cloud_api_key" value="<?= isset($data['google_cloud_api_key']) ? $data['google_cloud_api_key'] : "" ?>" class="width-300 margin" holder="<?= $mb->_translateReturn("forms", "form-pos-printer-google-api") ?>" />
			<input type="text" name="google_cloud_secret_key" id="google_cloud_secret_key" value="<?= isset($data['google_cloud_secret_key']) ? $data['google_cloud_secret_key'] : "" ?>" class="width-300 margin" holder="<?= $mb->_translateReturn("forms", "form-pos-printer-google-secret") ?>" />
			<input type="text" name="google_cloud_printer_id" id="google_cloud_printer_id" value="<?= isset($data['google_cloud_printer_id']) ? $data['google_cloud_printer_id'] : "" ?>" class="width-300" holder="<?= $mb->_translateReturn("forms", "form-pos-printer-google-id") ?>" />
		</div>
		
		<div class="form-content">
			<div class="content-header">
				<span class="fa fa-pencil-square-o"></span>
				<?= $mb->_translateReturn("forms", "legend-options") ?>
			</div>
		
			<input type="checkbox" <?= isset($data['auto_receipt']) && $data['auto_receipt'] == 1 ? "checked=\"checked\"" : "" ?> name="auto_receipt" id="auto_receipt" value="1" class="double-margin" holder="<?= $mb->_translateReturn("forms", "form-pos-printer-auto-receipt") ?>" />
			<input type="checkbox" <?= isset($data['auto_invoice']) && $data['auto_invoice'] == 1 ? "checked=\"checked\"" : "" ?> name="auto_invoice" id="auto_invoice" value="1" class="margin" holder="<?= $mb->_translateReturn("forms", "form-pos-printer-auto-invoice") ?>" />
			<input type="checkbox" <?= isset($data['auto_picklist']) && $data['auto_picklist'] == 1 ? "checked=\"checked\"" : "" ?> name="auto_picklist" id="auto_picklist" value="1" class="margin" holder="<?= $mb->_translateReturn("forms", "form-pos-printer-auto-picklist") ?>" />
		</div>
	</div>
</form>
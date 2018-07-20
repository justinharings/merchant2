<?php
$mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "TPB_FK", 1));
$data = $mb->_runFunction("cms", "loadInvoiceText", array($_SESSION['merchantID']));
?>

<ul class="breadcrumbs">
	<li>Merchant</li>
	<li><?= $mb->_translateReturn("menu", "cms") ?></li>
	<li><?= $mb->_translateReturn("menu", "invoice-content") ?></li>
</ul>

<form method="post" id="form" action="/library/php/posts/cms/facturatie.php">
	<input type="hidden" name="returnURL" id="returnURL" value="<?= "/" . _LANGUAGE_PACK . "/modules/" . $_GET['module'] . "/" . $_GET['file'] ?>" />
	
	<div class="simple-form">
		<div class="form-header">
			<h1><?= $mb->_translateReturn("menu", "invoice-content") ?></h1>
			
			<input type="button" name="return" id="return" value="<?= $mb->_translateReturn("forms", "button-cancel") ?>" class="show-load" />
			<input type="submit" name="save" id="save" value="<?= $mb->_translateReturn("forms", "button-save") ?>" class="red show-load validate-form" />
		</div>
		
		<div class="form-content">
			<div class="content-header">
				<span class="fa fa-pencil-square-o"></span>
				<?= $mb->_translateReturn("forms", "legend-invoice-text") ?>
			</div>
			
			<textarea name="invoice_text" id="invoice_text" class="width-100-percent margin" holder="<?= $mb->_translateReturn("forms", "form-cms-invoices-invoice") ?>" holder-eg="<?= $mb->_translateReturn("forms", "form-cms-invoices-invoice-eg") ?>"><?= (isset($data['invoice_text']) ? $data['invoice_text'] : "") ?></textarea>
			
			<?php
			$_lang = $mb->_allLanguages();
			
			if(count($_lang) > 1)
			{
				?>
				<div class="languages width-400 no-margin">
					<span class="fa fa-chevron-circle-down"></span>
					
					<?php
					foreach($_lang AS $value)
					{
						?>
						<fieldset>
							<legend><?= $value['language'] ?></legend>
							
							<textarea name="<?= $value['code'] ?>_invoice_text" id="<?= $value['code'] ?>_invoice_text" class="width-100-percent"><?= (isset($data[$value['code'] . '_invoice_text']) ? $data[$value['code'] . '_invoice_text'] : "") ?></textarea>
						</fieldset>
						<?php
					}
					?>
				</div>
				<?php
			}
			?>
		</div>
		
		<div class="form-content">
			<div class="content-header">
				<span class="fa fa-pencil-square-o"></span>
				<?= $mb->_translateReturn("forms", "legend-receipt-text") ?>
			</div>
			
			<textarea name="receipt_text" id="receipt_text" class="width-100-percent margin" holder="<?= $mb->_translateReturn("forms", "form-cms-invoices-receipt") ?>"><?= (isset($data['receipt_text']) ? $data['receipt_text'] : "") ?></textarea>
			
			<?php
			$_lang = $mb->_allLanguages();
			
			if(count($_lang) > 1)
			{
				?>
				<div class="languages width-400 no-margin">
					<span class="fa fa-chevron-circle-down"></span>
					
					<?php
					foreach($_lang AS $value)
					{
						?>
						<fieldset>
							<legend><?= $value['language'] ?></legend>
							
							<textarea name="<?= $value['code'] ?>_receipt_text" id="<?= $value['code'] ?>_receipt_text" class="width-100-percent"><?= (isset($data[$value['code'] . '_receipt_text']) ? $data[$value['code'] . '_receipt_text'] : "") ?></textarea>
						</fieldset>
						<?php
					}
					?>
				</div>
				<?php
			}
			?>
		</div>
		
		<div class="form-content">
			<div class="content-header">
				<span class="fa fa-pencil-square-o"></span>
				<?= $mb->_translateReturn("forms", "legend-invoice-extra") ?>
			</div>
			
			<textarea name="invoice_extra" id="invoice_extra" class="width-100-percent margin" holder="<?= $mb->_translateReturn("forms", "form-cms-invoices-invoice-extra") ?>" holder-eg="<?= $mb->_translateReturn("forms", "form-cms-invoices-invoice-extra-eg") ?>"><?= (isset($data['invoice_extra']) ? $data['invoice_extra'] : "") ?></textarea>
			
			<?php
			$_lang = $mb->_allLanguages();
			
			if(count($_lang) > 1)
			{
				?>
				<div class="languages width-400 no-margin">
					<span class="fa fa-chevron-circle-down"></span>
					
					<?php
					foreach($_lang AS $value)
					{
						?>
						<fieldset>
							<legend><?= $value['language'] ?></legend>
							
							<textarea name="<?= $value['code'] ?>_invoice_extra" id="<?= $value['code'] ?>_invoice_extra" class="width-100-percent"><?= (isset($data[$value['code'] . '_invoice_extra']) ? $data[$value['code'] . '_invoice_extra'] : "") ?></textarea>
						</fieldset>
						<?php
					}
					?>
				</div>
				<?php
			}
			?>
		</div>
	</div>
</form>
<?php
$data = $mb->_runFunction("postnl", "load", array($_SESSION['merchantID']));	
?>

<ul class="breadcrumbs">
	<li>Merchant</li>
	<li><?= $mb->_translateReturn("menu", "settings") ?></li>
	<li><?= $mb->_translateReturn("menu", "manage-postnl") ?></li>
</ul>

<form method="post" id="form" action="/library/php/posts/connections/postnl.php">
	<input type="hidden" name="returnURL" id="returnURL" value="<?= "/" . _LANGUAGE_PACK . "/modules/" . $_GET['module'] . "/" . $_GET['file'] ?>" />
	
	<div class="simple-form">
		<div class="form-header">
			<h1>PostNL Koppeling</h1>
			
			<input type="submit" name="save" id="save" value="<?= $mb->_translateReturn("forms", "button-save") ?>" class="red show-load validate-form" />
		</div>
		
		<div class="form-content">
			<div class="content-header">
				<span class="fa fa-pencil-square-o"></span>
				<?= $mb->_translateReturn("forms", "legend-apis") ?>
			</div>
			
			<input type="text" name="contactperson" id="contactperson" value="<?= isset($data['contactperson']) ? $data['contactperson'] : "" ?>" class="width-300 double-margin" holder="<?= $mb->_translateReturn("forms", "form-postnl-contactperson") ?>" validation-required="true" validation-type="text" />
			
			<input type="text" name="customer_code" id="customer_code" value="<?= isset($data['customer_code']) ? $data['customer_code'] : "" ?>" class="width-200 margin" holder="<?= $mb->_translateReturn("forms", "form-postnl-customercode") ?>" validation-required="true" validation-type="text" />
			<input type="text" name="customer_number" id="customer_number" value="<?= isset($data['customer_number']) ? $data['customer_number'] : "" ?>" class="width-200 double-margin" holder="<?= $mb->_translateReturn("forms", "form-postnl-customernumber") ?>" validation-required="true" validation-type="text" />
			<input type="text" name="api_key" id="api_key" value="<?= isset($data['api_key']) ? $data['api_key'] : "" ?>" class="width-300 margin" holder="<?= $mb->_translateReturn("forms", "form-postnl-apikey") ?>" validation-required="true" validation-type="text" />
		</div>
	</div>
</form>
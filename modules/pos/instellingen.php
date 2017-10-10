<?php
$mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "POS_KI", 1));
$data = $mb->_runFunction("pos", "loadGeneralSettings", array($_SESSION['merchantID']));
?>

<ul class="breadcrumbs">
	<li>Merchant</li>
	<li><?= $mb->_translateReturn("menu", "pos") ?></li>
	<li><?= $mb->_translateReturn("menu", "pos-settings") ?></li>
</ul>

<form method="post" id="form" action="/library/php/posts/pos/instellingen.php">
	<input type="hidden" name="returnURL" id="returnURL" value="<?= "/" . _LANGUAGE_PACK . "/modules/" . $_GET['module'] . "/" . $_GET['file'] ?>" />
	
	<div class="simple-form">
		<div class="form-header">
			<h1><?= $mb->_translateReturn("menu", "pos-settings") ?></h1>
			
			<input type="button" name="return" id="return" value="<?= $mb->_translateReturn("forms", "button-cancel") ?>" class="show-load" />
			<input type="submit" name="save" id="save" value="<?= $mb->_translateReturn("forms", "button-save") ?>" class="red show-load validate-form" />
		</div>
		
		<div class="form-content">
			<div class="content-header">
				<span class="fa fa-pencil-square-o"></span>
				<?= $mb->_translateReturn("forms", "legend-standard-data") ?>
			</div>
			
			<select name="shipmentID" id="shipmentID" class="width-300 margin" holder="<?= $mb->_translateReturn("forms", "form-pos-settings-shipment") ?>">
				<option value=""></option>
				
				<?php
				$_taxes = $mb->_runFunction("shipment_methods", "view", array($_SESSION['merchantID'], "", "shipment_methods.name", 50));
				
				foreach($_taxes AS $key => $value)
				{
					?>
					<option <?= isset($data['shipmentID']) && $data['shipmentID'] == $value['shipmentID'] ? "selected=\"selected\"" : "" ?> value="<?= $value['shipmentID'] ?>"><?= $value['name'] ?></option>
					<?php
				}
				?>
			</select>
			
			<select name="statusID" id="statusID" class="width-300" holder="<?= $mb->_translateReturn("forms", "form-pos-settings-orderstatus") ?>">
				<option value=""></option>
				
				<?php
				$_taxes = $mb->_runFunction("order_statuses", "view", array($_SESSION['merchantID'], "", "order_statuses.name", 50));
				
				foreach($_taxes AS $key => $value)
				{
					?>
					<option <?= isset($data['statusID']) && $data['statusID'] == $value['statusID'] ? "selected=\"selected\"" : "" ?> value="<?= $value['statusID'] ?>"><?= $value['name'] ?></option>
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
		
			<input type="checkbox" <?= isset($data['shipment_required']) && $data['shipment_required'] == 1 ? "checked=\"checked\"" : "" ?> name="shipment_required" id="shipment_required" value="1" class="margin" holder="<?= $mb->_translateReturn("forms", "form-pos-settings-shipment-required") ?>" />
			<input type="checkbox" <?= isset($data['send_emails']) && $data['send_emails'] == 1 ? "checked=\"checked\"" : "" ?> name="send_emails" id="send_emails" value="1" holder="<?= $mb->_translateReturn("forms", "form-pos-settings-send-emails") ?>" />
		</div>
	</div>
</form>
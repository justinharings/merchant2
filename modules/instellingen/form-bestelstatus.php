<?php
$mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "SET_BB", 1));

if(isset($_GET['dataID']))
{
	$data = $mb->_runFunction("order_statuses", "load", array($_GET['dataID']));
}
?>

<ul class="breadcrumbs">
	<li>Merchant</li>
	<li><?= $mb->_translateReturn("menu", "settings") ?></li>
	<li><?= $mb->_translateReturn("menu", "order-statuses") ?></li>
	<li><?= (isset($_GET['dataID']) ? $data['name'] : $mb->_translateReturn("forms", "add")) ?></li>
</ul>

<form method="post" id="form" action="/library/php/posts/instellingen/bestelstatus.php">
	<input type="hidden" name="statusID" id="statusID" value="<?= isset($_GET['dataID']) ? $_GET['dataID'] : 0 ?>" />
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
			
			<input type="text" name="name" id="name" value="<?= isset($_GET['dataID']) ? $data['name'] : "" ?>" class="width-300 margin" holder="<?= $mb->_translateReturn("forms", "form-status-name") ?>" holder-eg="<?= $mb->_translateReturn("forms", "form-status-name-eg") ?>" validation-required="true" validation-type="text" />
			
			<div class="languages width-300 no-margin">
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
		</div>
		
		<div class="form-content">
			<div class="content-header">
				<span class="fa fa-pencil-square-o"></span>
				<?= $mb->_translateReturn("forms", "legend-options") ?>
			</div>
			
			<input type="checkbox" <?= isset($_GET['dataID']) && $data['default'] == 1 ? "checked=\"checked\"" : "" ?> name="default" id="default" value="1" class="double-margin" holder="<?= $mb->_translateReturn("forms", "form-status-default") ?>" />
			
			<input type="checkbox" <?= isset($_GET['dataID']) && $data['finished'] == 1 ? "checked=\"checked\"" : "" ?> name="finished" id="finished" value="1" class="margin" holder="<?= $mb->_translateReturn("forms", "form-status-finished") ?>" holder-eg="<?= $mb->_translateReturn("forms", "form-status-finished-eg") ?>" />
			<input type="checkbox" <?= isset($_GET['dataID']) && $data['declined'] == 1 ? "checked=\"checked\"" : "" ?> name="declined" id="declined" value="1" class="double-margin" holder="<?= $mb->_translateReturn("forms", "form-status-declined") ?>" holder-eg="<?= $mb->_translateReturn("forms", "form-status-declined-eg") ?>" />
			
			<input type="checkbox" <?= isset($_GET['dataID']) && $data['shipment_email'] == 1 ? "checked=\"checked\"" : "" ?> name="shipment_email" id="shipment_email" value="1" holder="<?= $mb->_translateReturn("forms", "form-status-email") ?>" />
		</div>
	</div>
</form>
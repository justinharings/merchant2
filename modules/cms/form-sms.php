<?php
$mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "SET_BP", 1));

if(isset($_GET['dataID']))
{
	$data = $mb->_runFunction("cms", "loadSms", array($_GET['dataID']));
}
?>

<ul class="breadcrumbs">
	<li>Merchant</li>
	<li><?= $mb->_translateReturn("menu", "cms") ?></li>
	<li><?= $mb->_translateReturn("menu", "sms-templates") ?></li>
	<li><?= (isset($_GET['dataID']) ? $data['name'] : $mb->_translateReturn("forms", "add")) ?></li>
</ul>

<form method="post" id="form" action="/library/php/posts/cms/sms.php">
	<input type="hidden" name="smsID" id="smsID" value="<?= isset($_GET['dataID']) ? $_GET['dataID'] : 0 ?>" />
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
			
			<input type="text" name="name" id="name" value="<?= isset($_GET['dataID']) ? $data['name'] : "" ?>" class="width-300 margin" holder="<?= $mb->_translateReturn("forms", "form-sms-name") ?>" holder-eg="<?= $mb->_translateReturn("forms", "form-sms-name-eg") ?>" validation-required="true" validation-type="text" />
					
			<select name="typeID" id="typeID" class="width-200 double-margin" holder="<?= $mb->_translateReturn("forms", "form-sms-type") ?>" validation-required="true" validation-type="int" question="Geef hier aan om wat voor een SMS het gaat. Doe voorzichtig met SMS berichten gezien veel klanten dit als irritant beschouwen.">>
				<option value="-"></option>
				
				<?php
				foreach($mb->_runFunction("cms", "getTemplateTypes", array("template_sms_type")) AS $typeID => $desc)
				{
					?>
					<option <?= isset($_GET['dataID']) && $data['typeID'] == $typeID ? "selected=\"selected\"" : "" ?> value="<?= $typeID ?>"><?= $desc ?></option>
					<?php
				}
				?>
			</select>
			
			<select name="language_code" id="language_code" class="width-200" holder="<?= $mb->_translateReturn("forms", "form-sms-language") ?>" holder-eg="<?= $mb->_translateReturn("forms", "form-sms-language-eg") ?>">
				<option <?= isset($_GET['dataID']) && $data['language_code'] == "nl" ? "selected=\"selected\"" : "" ?> value="nl">Nederlands</option>
				
				<?php
				$_lang = $mb->_allLanguages();
				
				foreach($_lang AS $value)
				{
					?>
					<option <?= isset($_GET['dataID']) && $data['language_code'] == $value['code'] ? "selected=\"selected\"" : "" ?> value="<?= $value['code'] ?>"><?= $value['language'] ?></option>
					<?php
				}
				?>
			</select>
		</div>
		
		<div class="form-content">
			<div class="content-header">
				<span class="fa fa-pencil-square-o"></span>
				<?= $mb->_translateReturn("forms", "legend-manage-content") ?>
			</div>
			
			<textarea name="content" id="content" class="width-400" holder="<?= $mb->_translateReturn("forms", "form-sms-content") ?>" holder-eg="<?= $mb->_translateReturn("forms", "form-sms-content-eg") ?>" validation-required="true" validation-type="text" max-characters="160"><?= isset($_GET['dataID']) ? $data['content'] : "" ?></textarea
		</div>
	</div>
</form>
<?php
$mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "TPB_BB", 1));

if(isset($_GET['dataID']))
{
	$data = $mb->_runFunction("cms", "loadBanner", array($_GET['dataID']));
}
?>

<ul class="breadcrumbs">
	<li>Merchant</li>
	<li><?= $mb->_translateReturn("menu", "cms") ?></li>
	<li><?= $mb->_translateReturn("menu", "manage-banners") ?></li>
	<li><?= (isset($_GET['dataID']) ? $data['name'] : $mb->_translateReturn("forms", "add")) ?></li>
</ul>

<form method="post" id="form" action="/library/php/posts/cms/banner.php" enctype="multipart/form-data">
	<input type="hidden" name="bannerID" id="bannerID" value="<?= isset($_GET['dataID']) ? $_GET['dataID'] : 0 ?>" />
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
			
			<input type="text" name="name" id="name" value="<?= isset($_GET['dataID']) ? $data['name'] : "" ?>" class="width-300 double-margin" holder="<?= $mb->_translateReturn("forms", "form-banners-name") ?>" holder-eg="<?= $mb->_translateReturn("forms", "form-banners-name-eg") ?>" validation-required="true" validation-type="text" />
			<input type="text" name="tag" id="tag" value="<?= isset($_GET['dataID']) ? $data['tag'] : "" ?>" class="width-300 margin" holder="<?= $mb->_translateReturn("forms", "form-banners-tag") ?>" holder-eg="<?= $mb->_translateReturn("forms", "form-banners-tag-eg") ?>" validation-required="true" validation-type="text" />
			<input type="text" name="url" id="url" value="<?= isset($_GET['dataID']) ? $data['url'] : "" ?>" class="width-300 double-margin" holder="<?= $mb->_translateReturn("forms", "form-banners-url") ?>" holder-eg="<?= $mb->_translateReturn("forms", "form-banners-url-eg") ?>" />
			
			<select name="language_code" id="language_code" class="width-200" holder="<?= $mb->_translateReturn("forms", "form-banners-language") ?>" holder-eg="<?= $mb->_translateReturn("forms", "form-banners-language-eg") ?>">
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
				<?= $mb->_translateReturn("forms", "legend-pictures") ?>
			</div>
			
			<input type="file" name="image" id="image" value="" class="width-300 margin" holder="<?= $mb->_translateReturn("forms", "form-banners-image") ?>" holder-eg="<?= $mb->_translateReturn("forms", "form-banners-image-eg") ?>" <?= isset($_GET['dataID']) ? "" : "validation-required=\"true\"" ?>  />
		</div>
	</div>
</form>
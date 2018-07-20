<?php
$mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "TPB_BB", 1));

if(isset($_GET['dataID']))
{
	$data = $mb->_runFunction("cms", "loadContent", array($_GET['dataID']));
}
?>

<ul class="breadcrumbs">
	<li>Merchant</li>
	<li><?= $mb->_translateReturn("menu", "cms") ?></li>
	<li><?= $mb->_translateReturn("menu", "page-content") ?></li>
	<li><?= (isset($_GET['dataID']) ? $data['name'] : $mb->_translateReturn("forms", "add")) ?></li>
</ul>

<form method="post" id="form" action="/library/php/posts/cms/content.php" enctype="multipart/form-data">
	<input type="hidden" name="contentID" id="contentID" value="<?= isset($_GET['dataID']) ? $_GET['dataID'] : 0 ?>" />
	<input type="hidden" name="returnURL" id="returnURL" value="<?= "/" . _LANGUAGE_PACK . "/modules/" . $_GET['module'] . "/" . $_GET['file'] ?>" />
	
	<div class="simple-form">
		<div class="form-header">
			<h1><?= (isset($_GET['dataID']) ? $mb->_translateReturn("forms", "edit", array($data['name'])) : $mb->_translateReturn("forms", "add-new-title")) ?></h1>
			
			<input type="button" name="return" id="return" value="<?= $mb->_translateReturn("forms", "button-cancel") ?>" class="show-load" />
			<input type="submit" name="save" id="save" value="<?= $mb->_translateReturn("forms", "button-save") ?>" class="red show-load validate-form" />
		</div>
		
		<div class="form-content">
			<div class="content-header">
				<span class="fa fa-pencil-square-o"></span>
				<?= $mb->_translateReturn("forms", "legend-general") ?>
			</div>
			
			<input type="text" name="name" id="name" value="<?= isset($_GET['dataID']) ? $data['name'] : "" ?>" class="width-300 margin" holder="<?= $mb->_translateReturn("forms", "form-content-name") ?>" holder-eg="<?= $mb->_translateReturn("forms", "form-content-name-eg") ?>" validation-required="true" validation-type="text" />

			<select name="language_code" id="language_code" class="width-200" holder="<?= $mb->_translateReturn("forms", "form-content-language") ?>" holder-eg="<?= $mb->_translateReturn("forms", "form-content-language-eg") ?>">
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
				<?= $mb->_translateReturn("forms", "legend-seo") ?>
			</div>
			
			<input type="text" name="seo_url" id="seo_url" value="<?= isset($_GET['dataID']) ? $data['seo_url'] : "" ?>" class="width-300 margin" holder="<?= $mb->_translateReturn("forms", "form-content-seo-url") ?>" holder-eg="<?= $mb->_translateReturn("forms", "form-content-seo-url-eg") ?>" validation-required="true" validation-type="text" />
			<input type="text" name="seo_keywords" id="seo_keywords" value="<?= isset($_GET['dataID']) ? $data['seo_keywords'] : "" ?>" class="width-300 margin" holder="<?= $mb->_translateReturn("forms", "form-content-seo-keywords") ?>" holder-eg="<?= $mb->_translateReturn("forms", "form-content-seo-keywords-eg") ?>" question="Vul hier de zoekwoorden in die zoekmachine gebruikers invullen om bij deze pagina tekst te komen. Maximaal vijf en komma gescheiden. Deze zoekwoorden zijn essentieel om er voor te zorgen dat zoekers bij u terecht komen!" />
			
			<textarea name="seo_description" id="seo_description" class="width-400" holder="<?= $mb->_translateReturn("forms", "form-content-seo-description") ?>" holder-eg="<?= $mb->_translateReturn("forms", "form-content-seo-description-eg") ?>" max-characters="250" question="Heeft iemand u gevonden op een zoekmachine? Dan zien ze deze korte samenvatting van de pagina eerst. Deze samenvatting kan iemand overhalen om naar uw website te gaan, dus vul hem duidelijk in. Kort maar krachtig!"><?= (isset($data['seo_description']) ? $data['seo_description'] : "") ?></textarea>
		</div>
		
		<div class="form-content">
			<div class="content-header">
				<span class="fa fa-pencil-square-o"></span>
				<?= $mb->_translateReturn("forms", "legend-manage-content") ?>
			</div>
			
			<textarea name="content" id="content" class="width-100-percent" holder="<?= $mb->_translateReturn("forms", "form-content-content") ?>" holder-eg="<?= $mb->_translateReturn("forms", "form-content-content-eg") ?>"><?= (isset($data['content']) ? $data['content'] : "") ?></textarea>
		</div>
	</div>
</form>
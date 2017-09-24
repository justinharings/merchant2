<?php
$mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "TPB_BB", 1));

if(isset($_GET['dataID']))
{
	$data = $mb->_runFunction("reviews", "load", array($_GET['dataID']));
}
?>

<ul class="breadcrumbs">
	<li>Merchant</li>
	<li><?= $mb->_translateReturn("menu", "catalog") ?></li>
	<li><?= $mb->_translateReturn("menu", "reviews-management") ?></li>
	<li><?= (isset($_GET['dataID']) ? $data['name'] : $mb->_translateReturn("forms", "add")) ?></li>
</ul>

<form method="post" id="form" action="/library/php/posts/catalogus/review.php">
	<input type="hidden" name="reviewID" id="reviewID" value="<?= isset($_GET['dataID']) ? $_GET['dataID'] : 0 ?>" />
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
			
			<input type="text" name="name" id="name" value="<?= isset($_GET['dataID']) ? $data['name'] : "" ?>" class="width-300 double-margin" holder="<?= $mb->_translateReturn("forms", "form-reviews-name") ?>" holder-eg="<?= $mb->_translateReturn("forms", "form-reviews-name-eg") ?>" validation-required="true" validation-type="text" />
			<input type="checkbox" <?= isset($_GET['dataID']) && $data['approved'] == 1 ? "checked=\"checked\"" : "" ?> name="approved" id="approved" value="1" holder="<?= $mb->_translateReturn("forms", "form-reviews-active") ?>" />
		</div>
		
		<div class="form-content">
			<div class="content-header">
				<span class="fa fa-pencil-square-o"></span>
				<?= $mb->_translateReturn("forms", "legend-manage-content") ?>
			</div>
			
			<select name="stars" id="stars" class="width-100 double-margin" holder="<?= $mb->_translateReturn("forms", "form-reviews-stars") ?>">
				<option <?= isset($_GET['dataID']) && $data['stars'] == 1 ? "selected=\"selected\"" : "" ?> value="1">1 ster</option>
				<option <?= isset($_GET['dataID']) && $data['stars'] == 2 ? "selected=\"selected\"" : "" ?> value="2">2 sterren</option>
				<option <?= isset($_GET['dataID']) && $data['stars'] == 3 ? "selected=\"selected\"" : "" ?> value="3">3 sterren</option>
				<option <?= isset($_GET['dataID']) && $data['stars'] == 4 ? "selected=\"selected\"" : "" ?> value="4">4 sterren</option>
				<option <?= isset($_GET['dataID']) && $data['stars'] == 5 ? "selected=\"selected\"" : "" ?> value="5">5 sterren</option>
			</select>
			
			<textarea name="description" id="description" class="width-100-percent" holder="<?= $mb->_translateReturn("forms", "form-reviews-description") ?>" validation-required="true" validation-type="text"><?= isset($_GET['dataID']) ? $data['description'] : "" ?></textarea>
		</div>
	</div>
</form>
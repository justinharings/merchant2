<?php
$mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "TPB_FB", 1));

if(isset($_GET['dataID']))
{
	$data = $mb->_runFunction("cms", "loadAlbum", array($_GET['dataID']));
}
?>

<ul class="breadcrumbs">
	<li>Merchant</li>
	<li><?= $mb->_translateReturn("menu", "cms") ?></li>
	<li><?= $mb->_translateReturn("menu", "image-gallery") ?></li>
	<li><?= (isset($_GET['dataID']) ? $data['name'] : $mb->_translateReturn("forms", "add")) ?></li>
</ul>

<form method="post" id="form" action="/library/php/posts/cms/fotoalbum.php" enctype="multipart/form-data">
	<input type="hidden" name="albumID" id="albumID" value="<?= isset($_GET['dataID']) ? $_GET['dataID'] : 0 ?>" />
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
			
			<input type="text" name="name" id="name" value="<?= isset($_GET['dataID']) ? $data['name'] : "" ?>" class="width-300 margin" holder="<?= $mb->_translateReturn("forms", "form-albums-name") ?>" validation-required="true" validation-type="text" />
			<input type="text" name="album_tags" id="album_tags" value="<?= isset($_GET['dataID']) ? $data['tags'] : "" ?>" class="width-300 margin" holder="<?= $mb->_translateReturn("forms", "form-albums-tags") ?>" holder-eg="<?= $mb->_translateReturn("forms", "form-albums-tags-eg") ?>" validation-required="true" validation-type="text" />
		</div>
		
		<div class="form-content">
			<div class="content-header">
				<span class="fa fa-pencil-square-o"></span>
				<?= $mb->_translateReturn("forms", "legend-manage-content") ?>
			</div>
			
			<textarea name="description" id="description" class="width-400" holder="<?= $mb->_translateReturn("forms", "form-albums-description") ?>" holder-eg="<?= $mb->_translateReturn("forms", "form-albums-description-eg") ?>" validation-required="true" validation-type="text"><?= isset($_GET['dataID']) ? $data['description'] : "" ?></textarea>
		</div>
		
		<div class="form-content">
			<table class="form-table">
				<thead>
					<tr>
						<td><?= $mb->_translateReturn("forms", "form-albums-table-image") ?></td>
						<td><?= $mb->_translateReturn("forms", "form-albums-table-thumb") ?></td>
						<td><?= $mb->_translateReturn("forms", "form-albums-table-tags") ?></td>
						<td width="1"><span class="add-row fa fa-plus-circle"></span></td>
					</tr>
				</thead>
				
				<tbody>
					<?php
					foreach($data['images'] AS $value)
					{
						?>
						<tr>
							<td>
								<?php	
								$image = $_SERVER['DOCUMENT_ROOT'] . "/library/media/albums/" . $value['itemID'] . ".png";
								
								if(file_exists($image))
								{
									?>
									<img src="/library/media/albums/<?= $value['itemID'] ?>.png" class="profile" />
									<?php
								}
								else
								{
									print "Geen foto";
								}
								?>
							</td>
							<td><span class="fa large <?= $value['thumb'] ? "fa-check-circle green" : "fa-times-circle red" ?>"></span></td>
							<td><?= $value['tags'] ?></td>
							<td>
								<?php
								if($value['thumb'] == 0)
								{
									?>
									<span class="remove-row fa fa-remove" post="/library/php/posts/cms/verwijder_album_item.php?itemID=<?= $value['itemID'] ?>&returnURL=<?= "/" . _LANGUAGE_PACK . "/modules/" . $_GET['module'] . "/" . $_GET['file'] . "/" . $_GET['form'] . "/" . $_GET['dataID'] ?>"></span>
									<?php
								}
								?>
							</td>
						</tr>
						<?php
					}
					?>
					
					<tr class="new-row">
						<td><input type="file" name="image[]" id="image_+" value="" class="width-200" validation-type="image" image-extension="png" /></td>
						<td><input type="checkbox" name="thumb[]" id="thumb_+" value="1" class="width-200" validation-required="true" validation-type="int" /></td>
						<td><input type="text" name="tags[]" id="tags_+" value="" class="width-200" /></td>
						<td colspan="2">&nbsp;</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</form>
<?php
$mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "TPB_BB", 1));

if(isset($_GET['dataID']))
{
	$data = $mb->_runFunction("categories", "load", array($_GET['dataID']));
}
?>

<ul class="breadcrumbs">
	<li>Merchant</li>
	<li><?= $mb->_translateReturn("menu", "catalog") ?></li>
	<li><?= $mb->_translateReturn("menu", "webshop-categories") ?></li>
	<li><?= (isset($_GET['dataID']) ? $data['name'] : $mb->_translateReturn("forms", "add")) ?></li>
</ul>

<form method="post" id="form" action="/library/php/posts/catalogus/categories.php" enctype="multipart/form-data">
	<input type="hidden" name="categoryID" id="categoryID" value="<?= isset($_GET['dataID']) ? $_GET['dataID'] : 0 ?>" />
	<input type="hidden" name="returnURL" id="returnURL" value="<?= "/" . _LANGUAGE_PACK . "/modules/" . $_GET['module'] . "/" . $_GET['file'] ?>" />
	
	<div class="simple-form">
		<div class="form-header">
			<h1><?= (isset($_GET['dataID']) ? $mb->_translateReturn("forms", "edit", array($data['name'])) : $mb->_translateReturn("forms", "add-new-title")) ?></h1>
			
			<input type="button" name="return" id="return" value="<?= $mb->_translateReturn("forms", "button-cancel") ?>" class="show-load" />
			
			<?php
			if(isset($_GET['dataID']) && $data['products'] == 0)
			{
				?>
				<input type="button" name="delete-item" id="delete-item" value="<?= $mb->_translateReturn("forms", "button-delete") ?>" class="white show-load" />
				<?php
			}
			?>
			
			<input type="submit" name="save" id="save" value="<?= $mb->_translateReturn("forms", "button-save") ?>" class="red show-load validate-form" />
		</div>
		
		<?php
		if(isset($_GET['dataID']))
		{
			?>
			<div class="form-tabs">
				<div class="fa fa-bars"></div>
				
				<div class="fa fa-pencil"></div>
				<div class="fa fa-filter"></div>
			</div>
			<?php
		}
		?>
		
		<div class="tab tab-1">
			<div class="form-content">
				<div class="content-header">
					<span class="fa fa-pencil-square-o"></span>
					<?= $mb->_translateReturn("forms", "legend-general") ?>
				</div>
				
				<input type="text" name="name" id="name" value="<?= isset($_GET['dataID']) ? $data['name'] : "" ?>" class="width-300 margin" holder="<?= $mb->_translateReturn("forms", "form-categories-name") ?>" holder-eg="<?= $mb->_translateReturn("forms", "form-categories-name-eg") ?>" validation-required="true" validation-type="text" />
				
				<div class="languages width-300">
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
				
				<select name="parentID" id="parentID" class="width-200 margin" holder="<?= $mb->_translateReturn("forms", "form-categories-parent") ?>">
					<option value=""></option>
					
					<?php
					$data_parent = $mb->_runFunction("categories", "view", array($_SESSION['merchantID'], "", "categories.parentID, categories.name", "0,50", 0));
					
					foreach($data_parent AS $value)
					{
						?>
						<option <?= isset($_GET['dataID']) && $data['parentID'] == $value['categoryID'] ? "selected=\"selected\"" : "" ?> value="<?= $value['categoryID'] ?>"><?= ($value['parentID'] > 0 ? "&nbsp;-&nbsp;" : "") . $value['name'] ?></option>
						
						<?php
						$data_child = $mb->_runFunction("categories", "view", array($_SESSION['merchantID'], "", "categories.parentID, categories.name", "0,50", $value['categoryID']));
						
						foreach($data_child AS $value_child)
						{
							?>
							<option <?= isset($_GET['dataID']) && $data['parentID'] == $value_child['categoryID'] ? "selected=\"selected\"" : "" ?> value="<?= $value_child['categoryID'] ?>">&nbsp;-&nbsp;<?= $value_child['name'] ?></option>
							<?php
						}
					}
					?>
				</select>
				
				<select name="stock_type" id="stock_type" class="width-300 double-margin" holder="<?= $mb->_translateReturn("forms", "form-categories-stock-type") ?>">
					<?php
					$data_stock = $mb->_runFunction("stock", "viewStockType", array());
					
					foreach($data_stock AS $stockType => $name)
					{
						?>
						<option <?= isset($_GET['dataID']) && $data['stock_type'] == $stockType ? "selected=\"selected\"" : "" ?> value="<?= $stockType ?>"><?= $name ?></option>
						<?php
					}
					?>
				</select>
				
				<input type="checkbox" <?= (isset($_GET['dataID']) && $data['active'] == 1) || !isset($_GET['dataID']) ? "checked=\"checked\"" : "" ?> name="active" id="active" value="1" holder="<?= $mb->_translateReturn("forms", "form-categories-active") ?>" />
			</div>
		</div>
		
		<div class="tab tab-2">
			<div class="form-content">
				<table class="form-table">
					<thead>
						<tr>
							<td width="500"><?= $mb->_translateReturn("forms", "form-categories-filters-name") ?></td>
							<td><?= $mb->_translateReturn("forms", "form-categories-filters-multiple") ?></td>
							<td width="1"><span class="add-row fa fa-plus-circle"></span></td>
						</tr>
					</thead>
					
					<tbody>
						<?php
						foreach($data['filters'] AS $value)
						{
							?>
							<tr>
								<td>
									<?= $value['name'] ?>
									<div class="languages width-300 no-margin" style="margin-top: 10px;">
										<span class="fa fa-chevron-circle-down"></span>
										
										<?php
										$_lang = $mb->_allLanguages();
										
										foreach($_lang AS $lang_value)
										{
											?>
											<fieldset style="width: calc(100% - 42px); margin: 5px 10px;">
												<legend><?= $lang_value['language'] ?></legend>
												<?= $value[$lang_value['code'] . '_name'] ?>
											</fieldset>
											<?php
										}
										?>
									</div>
								</td>
								<td class="hide-mobile"><span class="fa large <?= $value['multiple_choice'] ? "fa-check-circle green" : "fa-times-circle red" ?>"></span></td>
								<td>
									<span class="remove-row fa fa-remove" post="/library/php/posts/catalogus/verwijder_filter.php?filterID=<?= $value['filterID'] ?>&returnURL=<?= "/" . _LANGUAGE_PACK . "/modules/" . $_GET['module'] . "/" . $_GET['file'] . "/" .$_GET['form'] . "/" . $_GET['dataID'] ?>"></span>
								</td>
							</tr>
							<?php
						}
						?>
						
						<tr class="new-row">
							<td>
								<input type="text" name="filter_name[]" id="filter_name_+" value="" class="width-300 margin" validation-required="true" validation-type="text" />
								<div class="languages width-300 no-margin">
									<span class="fa fa-chevron-circle-down"></span>
									
									<?php
									$_lang = $mb->_allLanguages();
									
									foreach($_lang AS $value)
									{
										?>
										<fieldset style="width: calc(100% - 42px); margin: 5px 10px;">
											<legend><?= $value['language'] ?></legend>
											<input type="text" name="<?= $value['code'] ?>_filter_name[]" id="<?= $value['code'] ?>_filter_name_+" value="" class="width-100-percent" validation-required="true" validation-type="text" icon="fa-globe" />
										</fieldset>
										<?php
									}
									?>
								</div>
							</td>
							<td><input type="checkbox" name="multiple[]" id="multiple_+" value="1" /></td>
							<td colspan="2">&nbsp;</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</form>
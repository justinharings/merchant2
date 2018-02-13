<?php
if(isset($_GET['dataID']))
{
	$data = $mb->_runFunction("categories", "loadSpecification", array($_GET['dataID']));
}
?>

<ul class="breadcrumbs">
	<li>Merchant</li>
	<li><?= $mb->_translateReturn("menu", "catalog") ?></li>
	<li><?= $mb->_translateReturn("menu", "specification-table") ?></li>
	<li><?= (isset($_GET['dataID']) ? $data['name'] : $mb->_translateReturn("forms", "add")) ?></li>
</ul>

<form method="post" id="form" action="/library/php/posts/catalogus/specifications.php">
	<input type="hidden" name="specificationID" id="specificationID" value="<?= isset($_GET['dataID']) ? $_GET['dataID'] : 0 ?>" />
	<input type="hidden" name="returnURL" id="returnURL" value="<?= "/" . _LANGUAGE_PACK . "/modules/" . $_GET['module'] . "/" . $_GET['file'] ?>" />
	
	<div class="simple-form">
		<div class="form-header">
			<h1><?= (isset($_GET['dataID']) ? $mb->_translateReturn("forms", "edit", array($data['name'])) : $mb->_translateReturn("forms", "add-new-title")) ?></h1>
			
			<input type="button" name="return" id="return" value="<?= $mb->_translateReturn("forms", "button-cancel") ?>" class="show-load" />
			
			<?php
			if(!isset($_GET['duplicate']))
			{
				?>
				<input type="button" name="duplicate" id="duplicate" value="<?= $mb->_translateReturn("forms", "button-duplicate") ?>" class="show-load" />
				<?php
			}
			
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
			
			<input type="text" name="name" id="name" value="<?= isset($_GET['dataID']) ? $data['name'] : "" ?>" class="width-300" holder="<?= $mb->_translateReturn("forms", "form-specifications-name") ?>" validation-required="true" validation-type="text" />
		</div>
		
		<div class="form-content">
			<table class="form-table">
				<thead>
					<tr>
						<td width="300"><?= $mb->_translateReturn("forms", "form-products-properties-language") ?></td>
						<td><?= $mb->_translateReturn("forms", "form-products-properties-key") ?></td>
						<td><?= $mb->_translateReturn("forms", "form-products-properties-value") ?></td>
						<td width="1"><span class="add-row fa fa-plus-circle"></span></td>
					</tr>
				</thead>
				
				<tbody>
					<?php
					$_lang = $mb->_allLanguages();
					$_lang_abbr = array("nl" => "Nederlands");
					
					foreach($_lang AS $value)
					{
						$_lang_abbr[$value['code']] = $value['language'];
					}
						
					if(isset($_GET['duplicate']))
					{
						$num = 1;
						
						foreach($data['filters'] AS $value)
						{
							?>
							<tr>
								<td>
									<select name="filter_language[]" id="filter_language_<?= $num ?>" class="width-200">
										<option <?= $value['language'] == "nl" ? "selected=\"selected\"" : "" ?> value="nl">Nederlands</option>
										<?php
										foreach($_lang AS $lValue)
										{
											?>
											<option <?= $lValue['code'] == $value['language'] ? "selected=\"selected\"" : "" ?> value="<?= $lValue['code'] ?>"><?= $lValue['language'] ?></option>
											<?php
										}
										?>
									</select>
								</td>
								<td><input type="text" name="filter_key[]" id="filter_key_<?= $num ?>" value="<?= $value['key'] ?>" class="width-300" validation-required="true" validation-type="text" /></td>
								<td><input type="text" name="filter_value[]" id="filter_value_<?= $num ?>" value="<?= $value['value'] ?>" class="width-300" validation-required="true" validation-type="text" /></td>
								<td colspan="2">&nbsp;</td>
							</tr>
							<?php
								
							$num++;
						}
					}
					else
					{
						foreach($data['filters'] AS $value)
						{
							?>
							<tr>
								<td><?= $_lang_abbr[$value['language']] ?></td>
								<td><?= $value['key'] ?></td>
								<td><?= $value['value'] ?></td>
								<td>
									<span class="remove-row fa fa-remove" post="/library/php/posts/catalogus/verwijder_specificatie_filter.php?filterID=<?= $value['filterID'] ?>&returnURL=<?= "/" . _LANGUAGE_PACK . "/modules/" . $_GET['module'] . "/" . $_GET['file'] . "/" .$_GET['form'] . "/" . $_GET['dataID'] ?>"></span>
								</td>
							</tr>
							<?php
						}
					}
					?>
					
					<tr class="new-row">
						<td>
							<select name="filter_language[]" id="filter_language_+" class="width-200">
								<option <?= isset($_GET['dataID']) && $data['language_code'] == "nl" ? "selected=\"selected\"" : "" ?> value="nl">Nederlands</option>
								<?php
								foreach($_lang AS $value)
								{
									?>
									<option <?= isset($_GET['dataID']) && $data['language_code'] == $value['code'] ? "selected=\"selected\"" : "" ?> value="<?= $value['code'] ?>"><?= $value['language'] ?></option>
									<?php
								}
								?>
							</select>
						</td>
						<td><input type="text" name="filter_key[]" id="filter_key_+" value="" class="width-300" validation-required="true" validation-type="text" /></td>
						<td><input type="text" name="filter_value[]" id="filter_value_+" value="" class="width-300" validation-required="true" validation-type="text" /></td>
						<td colspan="2">&nbsp;</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</form>
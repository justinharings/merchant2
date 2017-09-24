<?php
$mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "POS_KM", 1));
$data = $mb->_runFunction("pos", "loadEmployeeSettings", array($_SESSION['merchantID']));
?>

<ul class="breadcrumbs">
	<li>Merchant</li>
	<li><?= $mb->_translateReturn("menu", "pos") ?></li>
	<li><?= $mb->_translateReturn("menu", "pos-employees") ?></li>
</ul>

<form method="post" id="form" action="/library/php/posts/pos/medewerkers.php" enctype="multipart/form-data">
	<input type="hidden" name="returnURL" id="returnURL" value="<?= "/" . _LANGUAGE_PACK . "/modules/" . $_GET['module'] . "/" . $_GET['file'] ?>" />
	
	<div class="simple-form">
		<div class="form-header">
			<h1><?= $mb->_translateReturn("menu", "pos-employees") ?></h1>
			
			<input type="button" name="return" id="return" value="<?= $mb->_translateReturn("forms", "button-cancel") ?>" class="show-load" />
			<input type="submit" name="save" id="save" value="<?= $mb->_translateReturn("forms", "button-save") ?>" class="red show-load validate-form" />
		</div>
		
		<div class="form-content">
			<table class="form-table">
				<thead>
					<tr>
						<td><?= $mb->_translateReturn("forms", "form-pos-employees-image") ?></td>
						<td><?= $mb->_translateReturn("forms", "form-pos-employees-employee") ?></td>
						<td><?= $mb->_translateReturn("forms", "form-pos-employees-location") ?></td>
						<td class="hide-mobile"><?= $mb->_translateReturn("forms", "form-pos-employees-added") ?></td>
						<td width="1"><span class="add-row fa fa-plus-circle"></span></td>
					</tr>
				</thead>
				
				<tbody>
					<?php
					foreach($data AS $value)
					{
						?>
						<tr>
							<td>
								<?php	
								$image = $_SERVER['DOCUMENT_ROOT'] . "/library/media/employee_pictures/" . $value['employeeID'] . ".png";
								
								if(file_exists($image))
								{
									?>
									<img src="/library/media/employee_pictures/<?= $value['employeeID'] ?>.png" class="profile" />
									<?php
								}
								else
								{
									print "Geen foto";
								}
								?>
							</td>
							<td><?= $value['name'] ?></td>
							<td><?= $value['location'] ?></td>
							<td class="hide-mobile"><?= $value['date_added'] ?></td>
							<td>
								<span class="remove-row fa fa-remove" post="/library/php/posts/pos/verwijder_medewerker.php?employeeID=<?= $value['employeeID'] ?>&returnURL=<?= "/" . _LANGUAGE_PACK . "/modules/" . $_GET['module'] . "/" . $_GET['file'] ?>"></span>
							</td>
						</tr>
						<?php
					}
					?>
					
					<tr class="new-row">
						<td><input type="file" name="profile_image[]" id="profile_image_+" value="" class="width-200" validation-type="image" image-width="400" image-height="400" image-extension="png" /></td>
						<td><input type="text" name="name[]" id="name_+" value="" class="width-200" validation-required="true" validation-type="text" /></td>
						<td>
							<select name="location[]" id="location_+" class="width-200">
								<?php
								$locations = $mb->_runFunction("stock", "viewLocations", array($_SESSION['merchantID'], "", "locations.name", "0,50"));
								
								foreach($locations AS $location)
								{
									?>
									<option value="<?= $location['locationID'] ?>"><?= $location['name'] ?></option>
									<?php
								}
								?>
							</select>
						</td>
						<td>&nbsp;</td>
						<td colspan="2">&nbsp;</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</form>
<?php
$mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "SET_BP", 1));
$data = $mb->_runFunction("reports", "viewArticleSuppliers", array($_SESSION['merchantID'], (isset($_POST['month']) ? $_POST['month'] : date("m")), (isset($_POST['year']) ? $_POST['year'] : date("Y"))));
?>

<ul class="breadcrumbs">
	<li>Merchant</li>
	<li><?= $mb->_translateReturn("menu", "reports") ?></li>
	<li><?= $mb->_translateReturn("menu", "articles-brands") ?></li>
</ul>

<form method="post" id="form">
	<br/><br/>
	<div class="simple-form">
		<div class="form-content">
			<div class="content-header">
				<span class="fa fa-pencil-square-o"></span>
				<?= $mb->_translateReturn("forms", "legend-report-settings") ?>
			</div>
			
			<select name="month" id="month" class="width-100 margin" holder="<?= $mb->_translateReturn("forms", "form-repports-month") ?>">
				<option <?= (!isset($_POST['month']) && date("m") == 1) || (isset($_POST['month']) && $_POST['month'] == 1) ? "selected=\"selected\"" : "" ?> value="1">Januari</option>
				<option <?= (!isset($_POST['month']) && date("m") == 2) || (isset($_POST['month']) && $_POST['month'] == 2) ? "selected=\"selected\"" : "" ?> value="2">Februari</option>
				<option <?= (!isset($_POST['month']) && date("m") == 3) || (isset($_POST['month']) && $_POST['month'] == 3) ? "selected=\"selected\"" : "" ?> value="3">Maart</option>
				<option <?= (!isset($_POST['month']) && date("m") == 4) || (isset($_POST['month']) && $_POST['month'] == 4) ? "selected=\"selected\"" : "" ?> value="4">April</option>
				<option <?= (!isset($_POST['month']) && date("m") == 5) || (isset($_POST['month']) && $_POST['month'] == 5) ? "selected=\"selected\"" : "" ?> value="5">Mei</option>
				<option <?= (!isset($_POST['month']) && date("m") == 6) || (isset($_POST['month']) && $_POST['month'] == 6) ? "selected=\"selected\"" : "" ?> value="6">Juni</option>
				<option <?= (!isset($_POST['month']) && date("m") == 7) || (isset($_POST['month']) && $_POST['month'] == 7) ? "selected=\"selected\"" : "" ?> value="7">Juli</option>
				<option <?= (!isset($_POST['month']) && date("m") == 8) || (isset($_POST['month']) && $_POST['month'] == 8) ? "selected=\"selected\"" : "" ?> value="8">Augustus</option>
				<option <?= (!isset($_POST['month']) && date("m") == 9) || (isset($_POST['month']) && $_POST['month'] == 9) ? "selected=\"selected\"" : "" ?> value="9">September</option>
				<option <?= (!isset($_POST['month']) && date("m") == 10) || (isset($_POST['month']) && $_POST['month'] == 10) ? "selected=\"selected\"" : "" ?> value="10">Oktober</option>
				<option <?= (!isset($_POST['month']) && date("m") == 11) || (isset($_POST['month']) && $_POST['month'] == 11) ? "selected=\"selected\"" : "" ?> value="11">November</option>
				<option <?= (!isset($_POST['month']) && date("m") == 12) || (isset($_POST['month']) && $_POST['month'] == 12) ? "selected=\"selected\"" : "" ?> value="12">December</option>
			</select>
			
			<select name="year" id="year" class="width-100 double-margin" holder="<?= $mb->_translateReturn("forms", "form-repports-year") ?>">
				<?php
				for($i = date("Y"); $i > (date("Y")-10); $i--)
				{
					?>
					<option <?= (!isset($_POST['year']) && date("Y") == $i) || (isset($_POST['year']) && $_POST['year'] == $i) ? "selected=\"selected\"" : "" ?> value="<?= $i ?>"><?= $i ?></option>
					<?php
				}
				?>
			</select>
			
			<br/>
			
			<input type="submit" name="start" id="start" class="red" value="Starten" />&nbsp;
			<input type="button" name="reset" id="reset" value="Huidige maand genereren" onclick="document.location.href = document.location.href;" />
		</div>
		
		<div class="form-content">
			<div class="content-header">
				<span class="fa fa-pencil-square-o"></span>
				
				<?= $mb->_translateReturn("forms", "legend-results") ?> voor 
				<?= sprintf('%02d', (isset($_POST['month']) ? $_POST['month'] : date("m"))) ?>-<?= isset($_POST['year']) ? $_POST['year'] : date("Y") ?>
			</div>
		
			<table class="form-table">
				<thead>
					<tr>
						<td><?= $mb->_translateReturn("forms", "form-reports-table-group") ?></td>
						<td><?= $mb->_translateReturn("forms", "form-reports-table-grand_total") ?></td>
						<td><?= $mb->_translateReturn("forms", "form-reports-table-quantity") ?></td>
					</tr>
				</thead>
				
				<tbody>
					<?php
					foreach($data AS $value)
					{
						?>
						<tr>
							<td><?= $value['brand'] ?></td>
							<td>&euro;&nbsp;<?= _frontend_float($value['grand_total']) ?></td>
							<td><?= $value['quantity'] ?> <?= $mb->_translateReturn("forms", "form-reports-table-quantity-inline") ?></td>
						</tr>
						<?php
					}
					?>
				</tbody>
			</table>
		</div>
	</div>
</form>
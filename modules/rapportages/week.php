<?php
$mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "SET_BP", 1));
?>

<ul class="breadcrumbs">
	<li>Merchant</li>
	<li><?= $mb->_translateReturn("menu", "reports") ?></li>
	<li><?= $mb->_translateReturn("menu", "week-register") ?></li>
</ul>

<form method="post" id="form">
	<br/><br/>
	<div class="simple-form">
		<div class="form-content">
			<div class="content-header">
				<span class="fa fa-pencil-square-o"></span>
				<?= $mb->_translateReturn("forms", "legend-report-settings") ?>
			</div>
			
			<select name="week" id="week" class="width-100 margin" holder="<?= $mb->_translateReturn("forms", "form-repports-week") ?>">
				<?php
				$current = date("W")-1;
				
				if($current == 0)
				{
					$current = 52;
				}
					
				for($i = 1; $i < 53; $i++)
				{
					if($i > ($current+1))
					{
						continue;
					}
					
					?>
					<option <?= (isset($_POST['week']) && intval($_POST['week']) == $i) || $current == $i ? "selected=\"selected\"" : "" ?> value="<?= $i ?>">Week <?= $i ?></option>
					<?php
				}
				?>
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
			<input type="button" name="reset" id="reset" value="Vorige week genereren" onclick="document.location.href = document.location.href;" />
		</div>
		
		<div class="form-content">
			<div class="content-header">
				<span class="fa fa-pencil-square-o"></span>
				
				<?= $mb->_translateReturn("forms", "legend-results") ?> voor weeknummer <?= isset($_POST['week']) ? intval($_POST['week']) : $current ?>
			</div>
		
			<table class="form-table">
				<thead>
					<tr>
						<td><?= $mb->_translateReturn("forms", "form-reports-table-payment_method") ?></td>
						<td><?= $mb->_translateReturn("forms", "form-reports-table-grand_total") ?></td>
					</tr>
				</thead>
				
				<tbody>
					<?php
					$data = $mb->_runFunction("reports", "closeRegister", array($_SESSION['merchantID'], "week_" . (isset($_POST['week']) ? (intval($_POST['week']) . "_" . intval($_POST['year'])) : ($current . "_" . date("Y")))));
					
					$total = 0;
					
					foreach($data[0] AS $name => $amount)
					{
						?>
						<tr>
							<td><?= $name ?></td>
							<td>&euro;&nbsp;<?= _frontend_float($amount) ?></td>
						</tr>
						<?php
							
						$total += $amount;
					}
					
					$dataChanges = $mb->_runFunction("reports", "loadRegisterChanges", array($_SESSION['merchantID'], "week_" . (isset($_POST['week']) ? (intval($_POST['week']) . "_" . intval($_POST['year'])) : ($current . "_" . date("Y")))));
					
					foreach($dataChanges AS $value)
					{
						?>
						<tr style="background-color: #f9f9f9;">
							<td>
								<small>
									&nbsp;&nbsp;&nbsp;Kasverschil <?= $value['payment_method'] ?>
								</small>
								
							</td>
							<td>
								<small>
									&nbsp;&nbsp;&nbsp;&euro;&nbsp;<?= _frontend_float($value['amount']) ?>
								</small>
							</td>
						</tr>
						<?php
						
						$total += $value['amount'];
					}
					?>
					
					<tr>
						<td><?= $mb->_translateReturn("forms", "form-reports-table-grand_total") ?></td>
						<td><strong>&euro;&nbsp;<?= _frontend_float($total) ?></strong></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</form>
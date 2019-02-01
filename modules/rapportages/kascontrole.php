<?php
$mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "SET_BP", 1));

$_REQUEST['date'] = str_replace("/", "", $_REQUEST['date']);
?>

<ul class="breadcrumbs">
	<li>Merchant</li>
	<li><?= $mb->_translateReturn("menu", "reports") ?></li>
	<li>Kas controle module</li>
</ul>


<br/><br/>
<div class="simple-form">
	<?php
	$closed = $mb->_runFunction("reports", "findComplete", array($_SESSION['merchantID'], isset($_REQUEST['date']) ? str_replace("/", "", $_REQUEST['date']) : date("d-m-Y")));
		
	if($closed == 0)
	{
		?>
		<form method="post" id="form" action="/library/php/posts/reports/kassluiten.php">
			<input type="hidden" name="returnURL" id="returnURL" value="/<?= _LANGUAGE_PACK ?>/modules/rapportages/kascontrole/date/<?= isset($_REQUEST['date']) ? $_REQUEST['date'] : date("d-m-Y") ?>/" />
			<input type="hidden" name="date" id="date" value="<?= isset($_REQUEST['date']) ? $_REQUEST['date'] : date("d-m-Y") ?>" />
			
			<div class="form-header">
				<h1>Kas controle module voor datum <?= isset($_REQUEST['date']) ? str_replace("/", "", $_REQUEST['date']) : date("d-m-Y") ?></h1>
				
				<input type="submit" name="save" id="save" value="Kasboek sluiten en doorgaan" class="red show-load validate-form" />
			</div>
		</form>
		<?php
	}
	?>
	
	<div class="form-content">
		<form method="post" id="form" action="/<?= _LANGUAGE_PACK ?>/modules/rapportages/kascontrole/"> 
			<div class="content-header">
				<span class="fa fa-pencil-square-o"></span>
				<?= $mb->_translateReturn("forms", "legend-report-settings") ?>
			</div>
			
			<input type="text" name="date" id="date" value="<?= isset($_REQUEST['date']) ? str_replace("/", "", $_REQUEST['date']) : date("d-m-Y") ?>" class="width-100 datepicker margin" icon="fa-calendar" />
			
			<input type="submit" name="start" id="start" class="red" value="Starten" />&nbsp;
			<input type="button" name="reset" id="reset" value="Gisteren inzien" onclick="document.location.href = document.location.href;" />
		</form>
	</div>
	
	<div class="form-content">
		<div class="content-header">
			<span class="fa fa-pencil-square-o"></span>
			
			<?= $mb->_translateReturn("forms", "legend-results") ?> omzet <?= isset($_REQUEST['date']) ? $_REQUEST['date'] : date("d-m-Y") ?>
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
				$data = $mb->_runFunction("reports", "closeRegister", array($_SESSION['merchantID'], "day_" . (isset($_REQUEST['date']) ? $_REQUEST['date'] : date("d-m-Y"))));
				
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
				
				$dataChanges = $mb->_runFunction("reports", "loadRegisterChanges", array($_SESSION['merchantID'], (isset($_REQUEST['date']) ? $_REQUEST['date'] : date("d-m-Y"))));
				
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
	
	<?php
	if($closed == 0)
	{
		?>
		<div class="form-content">
			<form method="post" id="form" action="/library/php/posts/reports/kascontrole.php">
				<input type="hidden" name="returnURL" id="returnURL" value="/<?= _LANGUAGE_PACK ?>/modules/rapportages/kascontrole/date/<?= isset($_REQUEST['date']) ? $_REQUEST['date'] : date("d-m-Y") ?>/" />
				<input type="hidden" name="date" id="date" value="<?= isset($_REQUEST['date']) ? $_REQUEST['date'] : date("d-m-Y") ?>" />
				
				<div class="content-header">
					<span class="fa fa-pencil-square-o"></span>
					Kasverschil toevoegen
				</div>
				
				<?php
				$paymentMethods = $mb->_runFunction("payment_methods", "view", array($_SESSION['merchantID'], "", "payment_methods.name", "0,50"));	
				?>
				
				<select name="paymentID" id="paymentID" class="width-200 margin" holder="Betalingsmethode">
					<?php
					foreach($paymentMethods AS $value)
					{
						?>
						<option value="<?= $value['paymentID'] ?>"><?= $value['name'] ?></option>
						<?php
					}
					?>
				</select>
				
				<input type="text" name="amount" id="amount" value="0.00" class="width-100 margin" holder="Bedrag" icon="fa-euro" question="Vul hier het bedrag in dat er verschilde op de bovenstaande betalingsmodule." />
				
				<input type="submit" name="add_register" id="add_register" class="red" value="Kasverschil opslaan" />&nbsp;
			</form>
		</div>
		<?php
	}
	?>
</div>
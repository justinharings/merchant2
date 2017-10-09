<?php
$mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "SET_BP", 1));
$data = $mb->_runFunction("reports", "viewStockReport", array($_SESSION['merchantID']));
?>

<ul class="breadcrumbs">
	<li>Merchant</li>
	<li><?= $mb->_translateReturn("menu", "reports") ?></li>
	<li><?= $mb->_translateReturn("menu", "calculate-stock") ?></li>
</ul>

<form method="post" id="form">
	<br/><br/>
	<div class="simple-form">
		<div class="form-content">
			<div class="content-header">
				<span class="fa fa-pencil-square-o"></span>
				
				<?= $mb->_translateReturn("forms", "legend-results") ?> voor 
				<?= sprintf('%02d', (date("d"))) ?>-<?= sprintf('%02d', (date("m"))) ?>-<?= date("Y") ?>
			</div>
		
			<table class="form-table">
				<thead>
					<tr>
						<td><?= $mb->_translateReturn("forms", "form-reports-table-group") ?></td>
						<td><?= $mb->_translateReturn("forms", "form-reports-table-stock") ?></td>
						<td><?= $mb->_translateReturn("forms", "form-reports-table-amount") ?></td>
					</tr>
				</thead>
				
				<tbody>
					<?php
					foreach($data AS $name => $value)
					{
						?>
						<tr>
							<td><?= $name ?></td>
							<td><?= $value['quantity'] ?> <?= $mb->_translateReturn("forms", "form-reports-table-quantity-inline") ?></td>
							<td>&euro;&nbsp;<?= _frontend_float($value['grand_total']) ?></td>
						</tr>
						<?php
					}
					?>
				</tbody>
			</table>
		</div>
	</div>
</form>
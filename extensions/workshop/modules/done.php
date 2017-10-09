<?php
$data = $mb->_runFunction("workorders", "view", array($_SESSION['merchantID'], (isset($_GET['search_string']) ? trim($_GET['search_string'], "/") : ""), "workorders.status ASC, workorders.expiration_date", "0,100"));
	
if($mb->num_rows($data))
{
	$num = 0;
	
	foreach($data AS $value)
	{
		if($value['status'] != 1)
		{
			continue;
		}
		
		$num++;
		?>
		
		<div class="workorder-item">
			<div class="numbers">
				# <?= $value['workorderID'] ?><br/>
			
				<?php
				switch($value['status'])
				{
					case 0:
						$text = "OPENSTAAND";
						$color = "red";
					break;
					
					case 1:
						$text = "AFGEROND";
						$color = "green";
					break;
					
					case 2:
						$text = "IN DE WACHT";
						$color = "orange";
					break;
				}
				?>
			
				<div class="label <?= $color ?>">
					<?= $text ?>
				</div>
			</div>
			
			<div class="workorder">
				<strong style="color: #d00000;">Werkorder:</strong><br/>
				<?= $value['workorder'] ?>
			</div>
			
			<div class="summary">
				<table>
					<tr>
						<td width="60">
							<span class="fa workorder-action fa-comments" phone_number="<?= $value['phone_number'] ?>" workorderID="<?= $value['workorderID'] ?>"></span>
						</td>
						
						<td width="60">
							<span class="fa workorder-action fa-pencil" workorderID="<?= $value['workorderID'] ?>"></span>
						</td>
						
						<td width="60">
							<span class="fa workorder-action fa-reply return-closed" workorderID="<?= $value['workorderID'] ?>"></span>
						</td>
						
						<td width="60">
							<span class="fa workorder-action fa-exclamation" workorderID="<?= $value['workorderID'] ?>"></span>
						</td>
						
						<td class="info-block">
							<small>Verloopdatum</small><br/>
							<?= $value['expiration_date'] ?>
						</td>
						
						<td class="info-block">
							<small>Sleutelnummer</small><br/>
							Nummer <?= $value['key_number'] ?>
						</td>
						
						<td class="info-block">
							<small>Totaal</small><br/>
							&euro;&nbsp;<?= _frontend_float($value['grand_total']) ?>
						</td>
						
						<td class="info-block">
							<small>Prioriteit</small><br/>
							<?= $value['priority'] ? "Hoog" : "Normaal" ?>
						</td>
					</tr>
				</table>
			</div>
		</div>
		<?php
	}
	
	if($num == 0)
	{
		?>
		<span class="no-workorders fa fa-check-circle"></span>
		<?php
	}
}
?>
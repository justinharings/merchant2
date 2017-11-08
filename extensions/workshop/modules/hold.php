<?php
$data = $mb->_runFunction("workorders", "view", array($_SESSION['merchantID'], (isset($_GET['search_string']) ? trim($_GET['search_string'], "/") : ""), "workorders.status ASC, workorders.expiration_date", "0,100"));
	
if($mb->num_rows($data))
{
	$num = 0;
	
	foreach($data AS $value)
	{
		if($value['status'] != 2)
		{
			continue;
		}
		
		$num++;
		?>
		
		<div class="workorder-item" <?= time() >= strtotime($value['expiration_date']) ? "style='background-color: #fef2f2;'" : ((strtotime($value['expiration_date']) - 172800) > time() ? "style='background-color: #f2fef5;'" : "") ?>>
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
				<?= strip_tags($value['workorder']) ?>
			</div>
			
			<div class="summary" <?= time() >= strtotime($value['expiration_date']) ? "style='background-color: #fae6e6;'" : ((strtotime($value['expiration_date']) - 172800) > time() ? "style='background-color: #e6faec;'" : "") ?>>
				<table>
					<tr>
						<td width="60">
							<span class="fa <?= $value['card_saved'] == 1 ? "workorder-action" : "" ?> fa-check" phone_number="<?= $value['phone_number'] ?>" workorderID="<?= $value['workorderID'] ?>" <?= $value['card_saved'] != 1 ? "style=\"color: #ccc;\"" : "" ?>></span>
						</td>
						
						<td width="60">
							<span class="fa workorder-action fa-pencil" workorderID="<?= $value['workorderID'] ?>"></span>
						</td>
						
						<td width="60">
							<span class="fa workorder-action fa-reply" workorderID="<?= $value['workorderID'] ?>"></span>
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
<?php
$data = $mb->_runFunction("workorders", "view", array($_SESSION['merchantID'], (isset($_GET['search_string']) ? trim($_GET['search_string'], "/") : ""), "workorders.status ASC", "0,100"));
	
if($mb->num_rows($data))
{
	$num = 0;
	
	foreach($data AS $value)
	{
		if($value['used_product'] == 0)
		{
			continue;
		}
		
		$battery_date = "";
		
		$query_batt = sprintf(
			"	SELECT		DATE_FORMAT(batteries_test.date_added, '%%d-%%m-%%Y') AS date_added
				FROM		batteries_test
				INNER JOIN	batteries ON batteries.batteryID = batteries_test.batteryID
				WHERE		batteries.customerID = %d
				ORDER BY	batteries_test.date_added DESC",
			$value['customerID']
		);
		$result_batt = $mb->query($query_batt);
		
		if($mb->num_rows($result_batt) > 0)
		{
			$row_batt = $mb->fetch_assoc($result_batt);
			$battery_date = $row_batt['date_added'];
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
				<?php
				if($value['customerID'] > 0)
				{
					?>
					<strong><?= $value['customer_name'] ?></strong><br/>
					<Br/>
					<?php
				}
				?>
				
				<strong style="color: #d00000;">Werkorder:</strong><br/>
				<?= strip_tags($value['workorder']) ?>
			</div>
			
			<div class="summary">
				<table>
					<tr>
						<td width="60">
							<?php
							$class = "";
								
							if	(
									$value['card_saved'] == 1
									&&
									(
										$value['customerID'] == 0
										||
										(
											$value['customerID'] > 0
											&& $value['note'] != ""
										)
									)
								)
							{
								$class = "workorder-action";
							}
							
							if($value['status'] == 0)
							{
								?>
								<span class="fa <?= $class != "" ? $class : "" ?> fa-check" phone_number="<?= $value['phone_number'] ?>" workorderID="<?= $value['workorderID'] ?>" <?= $class == "" ? "style=\"color: #ccc;\"" : "" ?>></span>
								<?php
							}
							else
							{
								?>
								<span class="fa workorder-action fa-reply return-closed" workorderID="<?= $value['workorderID'] ?>"></span>
								<?php
							}
							?>
						</td>
						
						<td width="60">
							<span class="fa workorder-action fa-pencil" workorderID="<?= $value['workorderID'] ?>"></span>
						</td>
						
						<td width="60">
							<span class="fa workorder-action fa-exclamation" workorderID="<?= $value['workorderID'] ?>"></span>
						</td>
						
						<td class="info-block">
							<small>Gebruikt</small><br/>
							&euro;&nbsp;<?= _frontend_float($value['grand_total']) ?>
						</td>
						
						<td class="info-block">
							<small>Inkoopprijs</small><br/>
							&euro;&nbsp;<?= _frontend_float($value['used_product_price']) ?>
						</td>
						
						<td class="info-block">
							<small>Totaal</small><br/>
							&euro;&nbsp;<?= _frontend_float($value['grand_total']+$value['used_product_price']) ?>
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
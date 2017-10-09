<div class="register-screen table-grid">
	<input type="text" name="search" id="search" value="" class="width-200 double-margin" icon="fa-search" />
	
	<div class="table-control up">
		<span class="fa fa-caret-up"></span>
	</div>
	
	<div class="table-control down">
		<span class="fa fa-caret-down"></span>
	</div>
	
	<table class="view">
		<thead>
			<tr>
				<td><?= $mb->_translateReturn("table-headers", "status") ?></td>
				<td><?= $mb->_translateReturn("table-headers", "customer") ?></td>
				<td><?= $mb->_translateReturn("table-headers", "expiration_date") ?></td>
				<td><?= $mb->_translateReturn("table-headers", "number") ?></td>
			</tr>
		</thead>
		
		<tbody>
			<?php
			$data = $mb->_runFunction("workorders", "view", array($_SESSION['merchantID'], (isset($_GET['search_string']) ? trim($_GET['search_string'], "/") : ""), "workorders.status ASC, workorders.expiration_date", "0,100"));
				
			if($mb->num_rows($data))
			{
				foreach($data AS $value)
				{
					?>
					<tr key="<?= $value['workorderID'] ?>">
						<td>
							<?php
							switch($value['status'])
							{
								case 0:
									$color = "red";
									$text = "OPEN";
								break;
								
								case 1:
									$color = "green";
									$text = "KLAAR";
								break;
								
								case 2:
									$color = "orange";
									$text = "IN WACHT";
								break;
							}
							?>
							
							<div class="status-block <?= $color ?>">
								<?= $text ?>
							</div>
							
							<?php
							if($value['note'] != "")
							{
								?>
								<div class="status-block blue">
									OPMERKING
								</div>
								<?php
							}
							?>
						</td>
						<td style="vertical-align: middle;"><?= ($value['customer_name'] ? $value['customer_name'] : "Geen klant") ?></td>
						<td style="vertical-align: middle;">
							Klaar op
							<?= $value['expiration_date'] ?>
							<?php
							if($value['priority'])
							{
								?>
								<span class="fa fa-exclamation-triangle red"></span>
								<?php
							}
							?>
						</td>
						<td style="vertical-align: middle;"><?= $value['key_number'] ?></td>
					</tr>
					<?php
				}
			}
			else
			{
				?>
				<tr>
					<td colspan="6" align="center"><?= $mb->_translateReturn("table-headers", "no-results") ?></td>
				</tr>
				<?php
			}
			?>
		</tbody>
	</table>
</div>

<div class="table-options">
	<input type="button" name="load_workorder" id="load_workorder" value="Werkbon naar kassa" class="red" />
	<div class="spacer"></div>
	<input type="button" name="new_workorder" id="new_workorder" value="Nieuwe werkbon" />
	<input type="button" name="edit_workorder" id="edit_workorder" value="Werkbon bewerken" />
	<div class="spacer"></div>
	<input type="button" name="workshop" id="workshop" value="Werkplaats scherm" />
</div>
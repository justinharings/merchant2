<div class="register-screen table-grid">
	<div class="table-control up">
		<span class="fa fa-caret-up"></span>
	</div>
	
	<div class="table-control down">
		<span class="fa fa-caret-down"></span>
	</div>
	
	<table class="view">
		<thead>
			<tr>
				<td><?= $mb->_translateReturn("table-headers", "employee") ?></td>
				<td><?= $mb->_translateReturn("table-headers", "customer") ?></td>
				<td><?= $mb->_translateReturn("table-headers", "products") ?></td>
				<td><?= $mb->_translateReturn("table-headers", "date") ?></td>
			</tr>
		</thead>
		
		<tbody>
			<?php
			$data = $mb->_runFunction("pos", "viewParking", array($_SESSION['merchantID'], "", "pos_parked.date_added DESC", "0,50"));
				
			if($mb->num_rows($data))
			{
				foreach($data AS $value)
				{
					$sessions = unserialize($value['sessions']);
					
					$employee = $mb->_runFunction("pos", "loadEmployee", array($sessions['employeeID']));
					$customer = $mb->_runFunction("customers", "load", array($sessions['customer']));
					?>
					<tr key="<?= $value['parkingID'] ?>">
						<td><?= $employee['name'] ?></td>
						<td><?= $customer['name'] != "" ? $customer['name'] : "n.v.t." ?></td>
						<td><?= count($sessions['cart']) ?> <?= $mb->_translateReturn("table-headers", "products-inline") ?></td>
						<td><?= $value['date_added'] ?></td>
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
	<input type="button" name="use_parked" id="use_parked" value="Bestelling inladen" class="red" />
</div>
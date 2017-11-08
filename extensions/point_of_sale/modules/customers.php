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
				<td><?= $mb->_translateReturn("table-headers", "name") ?></td>
				<td><?= $mb->_translateReturn("table-headers", "city") ?></td>
				<td><?= $mb->_translateReturn("table-headers", "zipcode") ?></td>
				<td><?= $mb->_translateReturn("table-headers", "phone") ?></td>
				<td><?= $mb->_translateReturn("table-headers", "customer_code") ?></td>
			</tr>
		</thead>
		
		<tbody>
			<?php
			$data = $mb->_runFunction("customers", "view", array($_SESSION['merchantID'], (isset($_GET['search_string']) ? trim($_GET['search_string'], "/") : ""), "customers.name", "0,50"));
				
			if($mb->num_rows($data))
			{
				foreach($data AS $value)
				{			
					$phone = ($value['mobile_phone'] != "" ? $value['mobile_phone'] : $value['phone']);
						
					?>
					<tr key="<?= $value['customerID'] ?>">
						<td><?= $value['name'] ?></td>
						<td><?= $value['city'] . ", " . $value['country'] ?></td>
						<td><?= $value['zip_code'] ?></td>
						<td><?= $phone == "" ? "Onbekend" : $phone ?></td>
						<td><?= $value['customer_code'] == "" ? "Onbekend" : $value['customer_code'] ?></td>
					</tr>
					<?php					
				}
			}
			else if(!isset($_GET['search']))
			{
				?>
				<tr>
					<td colspan="6" align="center"><?= $mb->_translateReturn("table-headers", "search-required") ?></td>
				</tr>
				<?php
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
	<input type="button" name="use_customer" id="use_customer" value="Klant gebruiken" class="red" />
	<div class="spacer"></div>
	<input type="button" name="new_customer" id="new_customer" value="Nieuwe klant" />
	<input type="button" name="edit_customer" id="edit_customer" value="Klant bewerken" />
	<div class="spacer"></div>
	<input type="button" name="scan_customer_card" id="scan_customer_card" value="Klantenkaart aanmaken" />
</div>
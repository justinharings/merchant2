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
				<td><?= $mb->_translateReturn("table-headers", "orderid") ?></td>
				<td><?= $mb->_translateReturn("table-headers", "date") ?></td>
				<td><?= $mb->_translateReturn("table-headers", "customer") ?></td>
				<td><?= $mb->_translateReturn("table-headers", "grand_total") ?></td>
				<td class="hide-mobile"><?= $mb->_translateReturn("table-headers", "status") ?></td>
			</tr>
		</thead>
		
		<tbody>
			<?php
			$data = $mb->_runFunction("orders", "view", array($_SESSION['merchantID'], (isset($_GET['search_string']) ? trim($_GET['search_string'], "/") : ""), "orders.date_added DESC", "0,50", 0));
				
			if($mb->num_rows($data))
			{
				foreach($data AS $value)
				{
					$color = "";
					$icon = "hashtag";
					
					if($value['employeeID'] == 0)
					{
						$color = "blue";
						$icon = "internet-explorer";
					}
					?>
					<tr key="<?= $value['orderID'] ?>">
						<td class="<?= $color ?>">
							<span class="order-ref fa fa-<?= $icon ?>"></span>
							<?= $value['order_reference'] ?>
						</td>
						<td><?= $value['date_added'] ?></td>
						<td><?= $value['customer_name'] ?></td>
						<td>&euro;&nbsp;<?= _frontend_float($value['grand_total']) ?></td>
						<td class="hide-mobile"><?= $value['status'] ?></td>
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
	<input type="button" name="print_invoice" id="print_invoice" value="Factuur afdrukken" class="red" />
	<input type="button" name="print_receipt" id="print_receipt" value="Kassabon afdrukken" class="red" />
	<div class="spacer"></div>
	<input type="button" name="print_tender" id="print_tender" value="Offerte afdrukken" />
	<input type="button" name="print_picklist" id="print_picklist" value="Pakbon afdrukken" />
</div>
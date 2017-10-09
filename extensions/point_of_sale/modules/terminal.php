<div class="register-screen small">
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
				<td><?= $mb->_translateReturn("table-headers", "payed") ?></td>
				<td><?= $mb->_translateReturn("table-headers", "grand_total") ?></td>
				<td class="hide-mobile"><?= $mb->_translateReturn("table-headers", "status") ?></td>
			</tr>
		</thead>
		
		<tbody>
			<?php
			$data = $mb->_runFunction("orders", "view", array($_SESSION['merchantID'], (isset($_GET['search_string']) ? trim($_GET['search_string'], "/") : ""), "orders.date_added DESC", "0,50", 1));
				
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
						<td>&euro;&nbsp;<?= _frontend_float($value['payed']) ?></td>
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

<div class="register-keyboard">
	<div class="line-1">
		<form id="barcode-form" method="post" action="/pos/modules/terminal/search/" onsubmit="location.href = this.action + this.barcode.value; return false;">
			<input type="text" name="barcode" id="barcode" class="width-100-percent margin" icon="fa-barcode" />
		</form>
	
		<div class="button first">
			<div class="pos-button fa fa-paper-plane red" cart="<?= isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0 ?>"></div>
		</div>
		
		<div class="button">
			<div class="pos-button fa fa-print" id="print_invoice"></div>
		</div>
		
		<div class="button">
			<div class="pos-button fa fa-file-text-o" id="print_receipt"></div>
		</div>
	</div>
	
	<div class="line-2">
		<div class="line-2-1"></div>
		
		<div class="spacer"></div>
		
		<div class="line-2-2">
			<div class="button first">
				<div class="pos-button keyboard">1</div>
			</div>
			
			<div class="button">
				<div class="pos-button keyboard">2</div>
			</div>
			
			<div class="button">
				<div class="pos-button keyboard">3</div>
			</div>
			
			<div class="button first">
				<div class="pos-button keyboard">4</div>
			</div>
			
			<div class="button">
				<div class="pos-button keyboard">5</div>
			</div>
			
			<div class="button">
				<div class="pos-button keyboard">6</div>
			</div>
			
			<div class="button first">
				<div class="pos-button keyboard">7</div>
			</div>
			
			<div class="button">
				<div class="pos-button keyboard">8</div>
			</div>
			
			<div class="button">
				<div class="pos-button keyboard">9</div>
			</div>
			
			<div class="button first"></div>
			
			<div class="button">
				<div class="pos-button keyboard">0</div>
			</div>
			
			<div class="button">
				<div class="pos-button fa fa-backward keyboard"></div>
			</div>
		</div>
		
		<div class="spacer"></div>
		
		<div class="line-2-3"></div>
	</div>
</div>
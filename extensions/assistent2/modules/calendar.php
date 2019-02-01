<div class="container linen">
	<div class="inner-container">
		<div class="title fa fa-calendar"></div>
		
		<div class="content scroll">
			<h1>Kalender overzicht</h1>
			
			<?php
			$query = sprintf(
				"	SELECT		ass2_calendar.*
					FROM		ass2_calendar
					ORDER BY	ass2_calendar.date ASC"
			);
			$result = $mb->query($query);
			
			while($row = $mb->fetch_assoc($result))
			{
				$order = $mb->_runFunction("orders", "load", array(intval($row['orderID'])));
				
				$url = "/assistent2/?module=order&orderID=" . $row['orderID'];
				
				if($row['ready'])
				{
					$url = "/assistent2/?module=ready&orderID=" . $row['orderID'];
				}
				?>
				<div class="table" browse="<?= $url ?>">
					<table>
						<tr>
							<td>
								<?php
								if($row['ready'])
								{
									?>
									<span class="fa fa-check" style="color: green;"></span>&nbsp;&nbsp;
									<?php
								}
								?>
								
								<strong>#<?= $order['order_reference'] ?></strong>
								&nbsp;&nbsp;>&nbsp;&nbsp;
								<strong><?= date("d-m-Y", strtotime($row['date'])) ?></strong>
								&nbsp;&nbsp;>&nbsp;&nbsp;
								<strong><?= ($order['customer']['name'] != "" ? $order['customer']['name'] : "<em>Kassa verkoop</em>") ?></strong>
								
								<hr/>
								
								<table class="form-table">
									<thead>
										<tr>
											<td>AC</td>
											<td>Barcode</td>
											<td>Aantal</td>
											<td>Product</td>
											<td>Prijs</td>
										</tr>
									</thead>
									
									<tbody>
										<?php
										if(count($order['products']) > 0)
										{
											foreach($order['products'] AS $product)
											{
												$calc_vat = ($product['taxrate'] / 100) + 1;
												$product['price_ex_vat'] = ($product['price'] / $calc_vat);
												?>
												<tr id="<?= $product['orderProductID'] ?>">
													<td><?= $product['article_code'] ?></td>
													<td><?= ($product['barcode'] != "" ? $product['barcode'] : "Onbekend") ?></td>
													<td><?= $product['quantity'] ?> stuk(s)</td>
													<td><?= $product['name'] ?></td>
													<td><?= $product['price'] ?></td>
												</tr>
												<?php
											}
										}
										?>
									</tbody>
								</table>
							</td>
						</tr>
					</table>
				</div>
				<?php
			}
			?>
		</div>
		
		<div class="footer">
			<div class="date-time-stamp">
				<?= date("d-m-Y H:i") ?> uur
			</div>
			
			<div class="button refresh fa fa-sync"></div>
			<div class="button vuurwerk fa fa-fire"></div>
			
			<div class="spacer"></div>
			
			<div class="button calendar fa fa-calendar"></div>
			<div class="button core_products fa fa-bullseye"></div>
			<div class="button cleanup fa fa-trash"></div>
		</div>
	</div>
</div>
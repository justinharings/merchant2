<?php
$order = $mb->_runFunction("orders", "load", array(intval($_GET['orderID'])));
?>
<div class="container mint-green">
	<div class="inner-container">
		<div class="title fa fa-check"></div>
		<div class="menu-button fa fa-bars"></div>
		
		<div class="menu">
			<ul>
				<li browse="/extensions/assistent2/library/php/ready.php?orderID=<?= intval($_GET['orderID']) ?>">
					<span class="fa fa-check"></span>
					Bestelling staat klaar
				</li>
				
				<li class="datepicker">
					<span class="fa fa-calendar"></span>
					Nieuwe datum plannen
					
					<form method="post" id="date_form" action="/extensions/assistent2/library/php/date.php">
						<input type="hidden" name="orderID" id="orderID" value="<?= intval($_GET['orderID']) ?>" />
						<input type="text" name="date" id="date" value="" class="datepicker" />
					</form>
				</li>
				
				<li browse="/extensions/assistent2/library/php/postpone.php?orderID=<?= intval($_GET['orderID']) ?>">
					<span class="fa fa-clock"></span>
					Kwartier uitstellen
				</li>
			</ul>
		</div>
		
		<div class="content">
			<h1>Order #<?= $order['order_reference'] ?> klaarzetten</h1>
			
			<div class="table">
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
			</div>
			
			<div class="table">
				<table>
					<thead>
						<tr>
							<td>Verzendmethode</td>
							<td width="110">Prijs</td>
							<td>Koerier</td>
							<td>Barcode	</td>
						</tr>
					</thead>
					
					<tbody>
						<?php
						$data_shipments = $mb->_runFunction("shipment_methods", "view", array(1, "", "shipment_methods.name", "0,50"));
							
						if(count($order['shipments']) > 0)
						{
							foreach($order['shipments'] AS $shipment)
							{
								?>
								<tr>
									<td>
										<?php
										foreach($data_shipments AS $method)
										{
											print ($shipment['shipmentID'] == $method['shipmentID'] ? $method['name'] : "");
										}
										?>
									</td>
									<td>&euro;&nbsp;<?= _frontend_float($shipment['price']) ?></td>
									<td><?= $shipment['courier'] ?></td>
									<td><?= ($shipment['track_code'] == "" ? "Onbekend" : $shipment['track_code']) ?></td>
								</tr>
								<?php
							}
						}
						?>
					</tbody>
				</table>
			</div>
			
			<table width="100%">
				<tr>
					<td valign="top" width="40%">
						<div class="table more-padding">
							<table>
								<tr>
									<td width="150">
										<?php
										if($order['customer']['name'] == "")
										{
											print "Dit betreft een kassa<br/>verkoop zonder<br/>klantgegvens. Toegevoegd<br/>via een point of sale.";
										}
										else
										{
											?>
											<?= $order['customer']['name'] ?><br/>
											<?= $order['customer']['address'] ?><br/>
											<?= $order['customer']['zip_code'] ?> <?= $order['customer']['city'] ?><br/>
											<?= $order['customer']['country'] ?><br/>
											<br/>
											<?php
											if($order['customer']['email_address'] != "")
											{
												?>
												<?= $order['customer']['email_address'] ?><br/>
												<?php
											}
											else
											{
												print "Geen e-mail adres.<br/>";
											}
											
											if($order['customer']['phone'] == "")
											{
												$order['customer']['phone'] = $order['customer']['mobile_phone'];	
											}
											
											if($order['customer']['phone'] != "")
											{
												print "Tel: ". $order['customer']['phone'];
											}
											else
											{
												print "Geen nummer.";
											}
										}
										?>
									</td>
									
									<td>
										<?php
										if($order['customer']['name'] == "")
										{
											?>
											Er zijn <strong><?= $order['customer']['count_orders'] ?> andere bestelling(en)</strong> met een <br/>
											totaal besteed bedrag<br/>
											van <strong>&euro; <?= _frontend_float($order['customer']['total_orders']) ?></strong>.
											<?php
										}
										else
										{
											if($order['customer']['count_orders'] > 0)
											{
												?>
												Er zijn <span style="font-weight: bold;"><?= $order['customer']['count_orders'] ?> andere bestelling(en)</span> met een
												totaal besteed bedrag van <span style="font-weight: bold;">&euro; <?= _frontend_float($order['customer']['total_orders']) ?></span>.
												Alle bestellingen van deze klant zijn inzichtelijk via het account.<br/>
												<Br/>
												<?php
											}
											else
											{
												?>
												Dit is de eerste bestelling op dit account. Wanneer de klant straks meerdere bestellingen heeft
												staat er hier een samenvatting daarover.<br/>
												<br/>
												
												<?php
											}
										}
										?>
									</td>
								</tr>
							</table>
						</div>
					</td>
					
					<td width="2%"></td>
					
					<td class="58%;" valign="top">
						<div class="table">
							<table>
								<thead>
									<tr>
										<td width="175">Titel</td>
										<td>Waarde</td>
									</tr>
								</thead>
								
								<tbody>
									<?php
									$cnt = 0;
										
									for($i = 1; $i <= 4; $i++)
									{
										if($order['invoice_rules'][$i-1]['key'] == "")
										{
											continue;
										}
										?>
										<tr>
											<td><?= $order['invoice_rules'][$i-1]['key'] ?></td>
											<td><?= $order['invoice_rules'][$i-1]['value'] ?></td>
										</tr>
										<?php
											
										$cnt++;
									}
									
									if($cnt == 0)
									{
										?>
										<tr>
											<td colspan="2">Geen factuurregels toegevoegd.</td>
										</tr>
										<?php
									}
									?>
								</tbody>
							</table>
						</div>
					</td>
				</tr>
			</table>
		</div>
		
		<div class="footer">
			<div class="date-time-stamp">
				<?= date("d-m-Y H:i") ?> uur
			</div>
			
			<div class="button refresh fa fa-sync"></div>
			<div class="button vuurwerk fa fa-fire"></div>
			
			<div class="spacer"></div>
			
			<div class="button calendar fa fa-calendar"></div>
		</div>
	</div>
</div>
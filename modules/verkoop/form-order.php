<?php
$mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "SET_BP", 1));
$merchant = $mb->_runFunction("merchant", "load", array($_SESSION['merchantID']));

if(isset($_GET['dataID']))
{
	$data = $mb->_runFunction("orders", "load", array($_GET['dataID']));
}

$trackers = array();
$cnt = 0;

foreach($data['shipments'] AS $shipment)
{
	if($shipment['track_code'] != "")
	{
		$trackers[$cnt]['courier'] = $shipment['courier'];
		$trackers[$cnt]['track_code'] = $shipment['track_code'];
	}
	
	$cnt++;
}

$country_code = $mb->_countryCodes($data['customer']['country']);
?>

<ul class="breadcrumbs">
	<li>Merchant</li>
	<li><?= $mb->_translateReturn("menu", "sales") ?></li>
	<li><?= (isset($_GET['dataID']) ? "Bestelling #" . $data['order_reference'] : $mb->_translateReturn("forms", "add")) ?></li>
</ul>

<form method="post" id="form" action="/library/php/posts/verkoop/save.php">
	<input type="hidden" name="orderID" id="orderID" value="<?= isset($_GET['dataID']) ? $_GET['dataID'] : 0 ?>" />
	<input type="hidden" name="customerID" id="customerID" value="<?= isset($_GET['dataID']) ? $data['customerID'] : 0 ?>" />
	<input type="hidden" name="merchantID" id="merchantID" value="<?= $_SESSION['merchantID'] ?>" />
	<input type="hidden" name="returnURL" id="returnURL" value="<?= "/" . _LANGUAGE_PACK . "/modules/" . $_GET['module'] . "/" . $_GET['file'] . "/" . $_GET['form'] . "/" . $_GET['dataID'] ?>" />
	
	<div class="simple-form">
		<div class="form-header">
			<h1><?= (isset($_GET['dataID']) ? $mb->_translateReturn("forms", "edit", array("Bestelling " . $data['order_reference'])) : $mb->_translateReturn("forms", "add-new-title")) ?></h1>
			
			<input type="button" name="return" id="return" value="<?= $mb->_translateReturn("forms", "button-cancel") ?>" class="show-load" />
			<input type="button" name="print" id="print" value="<?= $mb->_translateReturn("forms", "button-print") ?>" class="pulldown" menu="print" />
			<input type="submit" name="save" id="save" value="<?= $mb->_translateReturn("forms", "button-save") ?>" class="red show-load validate-form" />
			
			<div class="pulldown print">
				<div class="item" window="/extensions/printserver/index.php?type=invoice&action=print&orderID=<?= $_GET['dataID'] ?>">
					<?= $mb->_translateReturn("forms", "button-invoice") ?>
				</div>
				
				<div class="item" window="/extensions/printserver/index.php?type=picklist&action=print&orderID=<?= $_GET['dataID'] ?>">
					<?= $mb->_translateReturn("forms", "button-picklist") ?>
				</div>
				
				<div class="item" window="/extensions/printserver/index.php?type=tender&action=print&orderID=<?= $_GET['dataID'] ?>">
					<?= $mb->_translateReturn("forms", "button-tender") ?>
				</div>
			</div>
		</div>
		

		<div class="form-tabs">
			<div class="fa fa-bars"></div>
			
			<div class="fa fa-id-card"></div>
			<div class="fa fa-tags"></div>
			<div class="fa fa-plane"></div>
			<div class="fa fa-credit-card"></div>
			<div class="fa fa-list"></div>
			<div class="fa fa-sticky-note"></div>
			
			<?php
			if($data['customer']['email_address'] != "")
			{
				?>
				<div class="fa fa-at"></div>
				<?php
			}
			
			if($data['customer']['mobile_phone'] != "")
			{
				?>
				<div class="fa fa-mobile-phone"></div>
				<?php
			}
			?>
		</div>
		
		<div class="tab tab-1">
			<div class="form-content">
				<div class="content-header">
					<span class="fa fa-pencil-square-o"></span>
					<?= $mb->_translateReturn("forms", "legend-order-information") ?>
				</div>
				
				<table>
					<tr>
						<td style="padding: 0px 20px 20px 0px;"><strong>Vestiging:</strong></td>
						<td style="padding: 0px 20px 20px 0px;" colspan="2"><?= $data['location'] ?></td>
					</tr>
					
					<tr>
						<td style="padding: 0px 20px 20px 0px;">
							<strong><?= $mb->_translateReturn("forms", "form-orders-orderid") ?></strong><br/>
							# <?= $data['order_reference'] ?>
						</td>
						
						<td style="padding: 0px 20px 20px 0px;">
							<strong><?= $mb->_translateReturn("forms", "form-orders-products") ?></strong><br/>
							
							<?php
							$qty = 0;
								
							foreach($data['products'] AS $product)
							{
								$qty += $product['quantity'];
							}
							
							print $qty . " " . $mb->_translateReturn("forms", "form-orders-products-inline")
							?>
							
						</td>
						
						<td style="padding: 0px 20px 20px 0px;">
							<strong><?= $mb->_translateReturn("forms", "form-orders-orderdate") ?></strong><br/>
							<?= $data['date_added'] ?>
						</td>
					</tr>
					
					<tr>
						<td style="padding: 0px 20px 0px 0px;">
							<strong><?= $mb->_translateReturn("forms", "form-orders-grand_total") ?></strong><br/>
							&euro;&nbsp;<?= _frontend_float($data['grand_total']) ?>
						</td>
						
						<td style="padding: 0px 20px 0px 0px;">
							<strong><?= $mb->_translateReturn("forms", "form-orders-payed") ?></strong><br/>
							&euro;&nbsp;<?= _frontend_float($data['payed']) ?>
						</td>
						
						<td style="padding: 0px 20px 0px 0px;">
							<strong><?= $mb->_translateReturn("forms", "form-orders-open") ?></strong><br/>
							<?php
							$open = $data['grand_total'] - $data['payed'];
							?>
							<span style="color: <?= ($open > 0 || $open < 0 ? "#d00000" : "") ?>;">&euro;&nbsp;<?= _frontend_float($open) ?></span>
							
							<?php
							if($open < 0)
							{
								?>
								<span class="fa fa-exclamation-triangle" style="color: <?= ($open > 0 || $open < 0 ? "#d00000" : "") ?>;"></span>
								<?php
							}
							?>
						</td>
					</tr>
				</table>
				
				<br/><br/>
				
				<select name="statusID" id="statusID" class="width-200 margin" holder="<?= $mb->_translateReturn("forms", "form-orders-status") ?>">
					<?php
					$data_status = $mb->_runFunction("order_statuses", "view", array($_SESSION['merchantID'], "", "order_statuses.name", "0,50"));
					
					foreach($data_status AS $status)
					{
						?>
						<option <?= $status['statusID'] == $data['statusID'] ? "selected=\"selected\"" : "" ?> value="<?= $status['statusID'] ?>"><?= $status['name'] ?></option>
						<?php
					}
					?>
				</select>
				
				<input type="checkbox" name="omboeken" id="omboeken" value="1" holder="<?= $mb->_translateReturn("forms", "form-orders-omboeken") ?>" />
			</div>
			
			<?php
			if(count($trackers) > 0)
			{
				?>
				<div class="form-content">
					<div class="content-header">
						<span class="fa fa-pencil-square-o"></span>
						<?= $mb->_translateReturn("forms", "legend-shipment-track") ?>
					</div>
					
					Er zijn in totaal <?= count($trackers) ?> verzendingen met track&trace.<br/>
					<br/>
					<table>
						<tr>
							<td style="padding: 0px 20px 0px 0px;"><strong>Barcode</strong></td>
							<td><strong>Koerier</strong></td>
						</tr>
						
						<?php
						foreach($trackers AS $track)
						{
							switch(strtolower($track['courier']))
							{
								default:
									continue;
								break;
								
								case "postnl":
									$url = "https://jouw.postnl.nl/?L=NL&B=" . $track['track_code'] . "&P=" . $data['customer']['zip_code'] . "&D=NL&T=C#!/overzicht";
								break;
							}
							?>
							<tr>
							<td style="padding: 0px 20px 0px 0px;">
								<a href="<?= $url ?>" target="_blank"><?= $track['track_code'] ?></a>
									&nbsp;<small><span class="fa fa-external-link"></span></small>
							</td>
							<td><?= $track['courier'] ?></td>
						</tr>
							<?php
						}
						?>
					</table>
				</div>
				<?php
			}
			?>
			
			<div class="form-content">
				<div class="content-header">
					<span class="fa fa-pencil-square-o"></span>
					<?= $mb->_translateReturn("forms", "legend-customer-information") ?>
				</div>
				
				<table>
					<tr>
						<td style="padding: 0px 50px 0px 0px;" valign="top">
							<?php
							if($data['customer']['name'] == "")
							{
								print "Dit betreft een kassa<br/>verkoop zonder<br/>klantgegvens. Toegevoegd<br/>via een point of sale.";
							}
							else
							{
								?>
								<a href="/<?= $_SESSION['language_pack'] ?>/modules/klanten/view/form-klant/<?= $data['customer']['customerID'] ?>/"><?= $data['customer']['name'] ?></a><br/>
								<?= $data['customer']['address'] ?><br/>
								<?= $data['customer']['zip_code'] ?> <?= $data['customer']['city'] ?><br/>
								<?= $data['customer']['country'] ?><br/>
								<br/>
								<?php
								if($data['customer']['email_address'] != "")
								{
									?>
									<a href="#" class="activate-tab" tab="7"><?= $data['customer']['email_address'] ?></a><br/>
									<?php
								}
								else
								{
									print "Geen e-mail adres.<br/>";
								}
								
								if($data['customer']['phone'] == "")
								{
									$data['customer']['phone'] = $data['customer']['mobile_phone'];	
								}
								
								if($data['customer']['phone'] != "")
								{
									print "Tel: ". $data['customer']['phone'];
								}
								else
								{
									print "Geen telefoonnummer.";
								}
							}
							?>
						</td>
						
						<td valign="top" width="200">
							<?php
							if($data['customer']['name'] == "")
							{
								?>
								Er zijn <strong><?= $data['customer']['count_orders'] ?> andere bestelling(en)</strong> met een <br/>
								totaal besteed bedrag<br/>
								van <strong>&euro; <?= _frontend_float($data['customer']['total_orders']) ?></strong>.
								<?php
							}
							else
							{
								if($data['customer']['count_orders'] > 0)
								{
									?>
									Er zijn <strong><?= $data['customer']['count_orders'] ?> andere bestelling(en)</strong> met een
									totaal besteed bedrag van <strong>&euro; <?= _frontend_float($data['customer']['total_orders']) ?></strong>.
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
								?>
								<a href="/<?= $_SESSION['language_pack'] ?>/modules/klanten/view/form-klant/<?= $data['customer']['customerID'] ?>/">Naar klant account &#187;</a>
								<?php
							}
							?>
						</td>
					</tr>
				</table>
			</div>
		</div>
		
		<div class="tab tab-2">
			<div class="form-content">
				<table class="form-table">
					<thead>
						<tr>
							<td><?= $mb->_translateReturn("table-headers", "ac") ?></td>
							<td><?= $mb->_translateReturn("table-headers", "barcode") ?></td>
							<td><?= $mb->_translateReturn("table-headers", "quantity") ?></td>
							<td><?= $mb->_translateReturn("table-headers", "product") ?></td>
							<td><?= $mb->_translateReturn("table-headers", "price") ?></td>
							<td><?= $mb->_translateReturn("table-headers", "grand_total") ?></td>
							<td><?= $mb->_translateReturn("table-headers", "price_ex_tax") ?></td>
							<td width="1"><span class="add-row fa fa-plus-circle"></span></td>
						</tr>
					</thead>
					
					<tbody>
						<?php
						if(count($data['products']) > 0)
						{
							foreach($data['products'] AS $product)
							{
								$calc_vat = ($product['taxrate'] / 100) + 1;
								$product['price_ex_vat'] = ($product['price'] / $calc_vat);
								?>
								<tr id="<?= $product['orderProductID'] ?>">
									<td>
										<a href="/<?= $_SESSION['language_pack'] ?>/modules/catalogus/artikelen/form-artikel/<?= $product['productID'] ?>/">
											<?= $product['article_code'] ?>
										</a>
										
										<input type="hidden" name="productID[]" id="productID_<?= $product['orderProductID'] ?>" value="<?= $product['productID'] ?>" />
										<input type="hidden" name="orderProductID[]" id="orderProductID_<?= $product['orderProductID'] ?>" value="<?= $product['orderProductID'] ?>" />
										<input type="hidden" name="taxrate[]" id="taxrate_<?= $product['orderProductID'] ?>" value="<?= $product['taxrate'] ?>" />
										<input type="hidden" name="name[]" id="name_<?= $product['orderProductID'] ?>" value="<?= $product['name'] ?>" />
									</td>
									<td><?= ($product['barcode'] != "" ? $product['barcode'] : "Onbekend") ?></td>
									<td>
										<input type="text" name="quantity[]" id="quantity_<?= $product['orderProductID'] ?>" value="<?= $product['quantity'] ?>" class="width-40 text-center calc-main-quantity" validation-type="int" validation-required="true" />
									</td>
									<td title="<?= strip_tags($product['name']) ?>"><?= _chopString($product['name'], 30) ?></td>
									<td>
										<input type="text" name="price[]" id="price_<?= $product['orderProductID'] ?>" value="<?= $product['price'] ?>" class="width-75 text-center calc-main-price" icon="fa-euro" validation-type="int" validation-required="true" />
									</td>
									<td>
										<input type="text" disabled="disabled" name="total_<?= $product['orderProductID'] ?>" id="total_<?= $product['orderProductID'] ?>" value="<?= _frontend_float($product['quantity']*$product['price']) ?>" class="width-75 text-center calc-total-price" icon="fa-euro" />
									</td>
									<td>
										<input type="text" disabled="disabled" name="excl_<?= $product['orderProductID'] ?>" id="excl_<?= $product['orderProductID'] ?>" value="<?= _frontend_float($product['price_ex_vat']) ?>" class="width-75 text-center calc-excl-price" icon="fa-euro" />
									</td>
									<td>
										<span class="remove-row fa fa-remove" post="/library/php/posts/verkoop/verwijder_product.php?itemID=<?= $product['orderProductID'] ?>&returnURL=<?= "/" . _LANGUAGE_PACK . "/modules/" . $_GET['module'] . "/" . $_GET['file'] . "/" . $_GET['form'] . "/" . $_GET['dataID'] ?>"></span>
									</td>
								</tr>
								<?php
							}
						}
						?>
						
						<tr class="new-row">
							<td class="searched-p-article-code"><input type="text" name="article_code[]" id="article_code_+" value="" class="width-75 product-search" icon="fa-search" validation-type="int" validation-required="true" /></td>
							<td class="searched-p-barcode"></td>
							<td><input type="text" name="quantity[]" id="quantity_+" value="1" class="width-40 text-center" validation-type="int" validation-required="true" /></td>
							<td class="searched-p-name"></td>
							<td><input type="text" name="price[]" id="price_+" value="" class="width-75 searched-p-price text-center" icon="fa-euro" validation-type="int" validation-required="true" /></td>
							<td>&nbsp;</td>
							<td>
								<input type="hidden" name="name[]" id="name_+" value="" class="searched-p-name" />
								<input type="hidden" name="taxrate[]" id="taxrate_+" value="" class="searched-p-taxrate" />
								<input type="hidden" name="orderProductID[]" id="orderProductID_+" value="0" />
							</td>
							<td class="searched-p-productID">
								<input type="hidden" name="productID[]" id="productID_+" value="0" />
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		
		<div class="tab tab-3">
			<div class="form-content">
				<table class="form-table">
					<thead>
						<tr>
							<td><?= $mb->_translateReturn("table-headers", "shipment-method") ?></td>
							<td><?= $mb->_translateReturn("table-headers", "normal-price") ?></td>
							<td width="110"><?= $mb->_translateReturn("table-headers", "price") ?></td>
							<td><?= $mb->_translateReturn("table-headers", "courier") ?></td>
							<td><?= $mb->_translateReturn("table-headers", "track-trace-code") ?></td>
							<td width="1"><span class="add-row fa fa-plus-circle"></span></td>
						</tr>
					</thead>
					
					<tbody>
						<?php
						$data_shipments = $mb->_runFunction("shipment_methods", "view", array($_SESSION['merchantID'], "", "shipment_methods.name", "0,50"));
							
						if(count($data['shipments']) > 0)
						{
							foreach($data['shipments'] AS $shipment)
							{
								?>
								<tr id="<?= $shipment['orderShipmentID'] ?>">
									<td>
										<select name="shipmentID[]" id="shipmentID_<?= $shipment['orderShipmentID'] ?>" class="width-150">
											<?php
											foreach($data_shipments AS $method)
											{
												?>
												<option <?= $shipment['shipmentID'] == $method['shipmentID'] ? "selected=\"selected\"" : "" ?> value="<?= $method['shipmentID'] ?>"><?= $method['name'] ?></option>
												<?php
											}
											?>
										</select>
										
										<input type="hidden" name="orderShipmentID[]" id="orderShipmentID_<?= $shipment['orderShipmentID'] ?>" value="<?= $shipment['orderShipmentID'] ?>" />
									</td>
									<td>&euro;&nbsp;<?= _frontend_float($shipment['normal_price']) ?></td>
									<td>
										<input type="text" name="ship_price[]" id="ship_price_<?= $shipment['orderShipmentID'] ?>" value="<?= $shipment['price'] ?>" class="width-75" icon="fa-euro" validation-type="int" validation-required="true" />
									</td>
									<td>
										<input type="text" name="courier[]" id="courier_<?= $shipment['orderShipmentID'] ?>" value="<?= $shipment['courier'] ?>" class="width-200" />
									</td>
									<td><input type="text" name="track_code[]" id="track_code_<?= $shipment['orderShipmentID'] ?>" class="width-200" value="<?= $shipment['track_code'] ?>" icon="fa-search" /></td>
									<td>
										<span class="remove-row fa fa-remove" post="/library/php/posts/verkoop/verwijder_verzending.php?itemID=<?= $shipment['orderShipmentID'] ?>&returnURL=<?= "/" . _LANGUAGE_PACK . "/modules/" . $_GET['module'] . "/" . $_GET['file'] . "/" . $_GET['form'] . "/" . $_GET['dataID'] ?>"></span>
									</td>
								</tr>
								<?php
							}
						}
						?>
						
						<tr class="new-row">
							<td>
								<select name="shipmentID[]" id="shipmentID_+" class="width-150 shipment-search">
									<option value=""></option>
									<?php
									foreach($data_shipments AS $method)
									{
										?>
										<option value="<?= $method['shipmentID'] ?>"><?= $method['name'] ?></option>
										<?php
									}
									?>
								</select>
							</td>
							<td class="searched-s-price"></td>
							<td><input type="text" name="ship_price[]" id="ship_price_+" value="" class="width-75 searched-s-price" icon="fa-euro" validation-type="int" validation-required="true" /></td>
							<td><input type="text" name="courier[]" id="courier_+" value="" class="width-200 searched-s-courier" /></td>
							<td><input type="text" name="track_code[]" id="track_code_+" class="width-200" value="" icon="fa-search" /></td>
							<td>
								<input type="hidden" name="orderShipmentID[]" id="orderShipmentID_+" value="0" />
							</td>
						</tr>
					</tbody>
				</table>
				
				<br/>
				
				<a href="/modules/verkoop/print-label.php?orderID=<?= intval($_GET['dataID']) ?>" target="_blank"><img src="/library/media/verzendlabel.png" /></a>
				&nbsp;
				<a href="/extensions/shipment/postnl/label.php?orderID=<?= ($_GET['dataID']) ?>" target="_blank"><img src="/library/media/postnl-print.png" /></a>
				
				<?php
				if($country_code == "NL")
				{
					?>
					&nbsp;
					<a href="/extensions/shipment/postnl/envelope.php?orderID=<?= ($_GET['dataID']) ?>" target="_blank"><img src="/library/media/enveloppe.png" /></a>
					<?php
				}
				?>
			</div>
		</div>
		
		<div class="tab tab-4">
			<div class="form-content">
				<table class="form-table">
					<thead>
						<tr>
							<td width="200"><?= $mb->_translateReturn("table-headers", "payment-method") ?></td>
							<td width="140"><?= $mb->_translateReturn("table-headers", "payed") ?></td>
							<td><?= $mb->_translateReturn("table-headers", "date") ?></td>
							<td width="1"><span class="add-row fa fa-plus-circle"></span></td>
						</tr>
					</thead>
					
					<tbody>
						<?php
						$data_payments = $mb->_runFunction("payment_methods", "view", array($_SESSION['merchantID'], "", "payment_methods.name", "0,50"));
							
						if(count($data['payments']) > 0)
						{
							foreach($data['payments'] AS $payment)
							{
								?>
								<tr id="<?= $payment['orderPaymentID'] ?>">
									<td>
										<select name="paymentID[]" id="paymentID_<?= $payment['orderPaymentID'] ?>" class="width-150">
											<?php
											foreach($data_payments AS $method)
											{
												?>
												<option <?= $payment['paymentID'] == $method['paymentID'] ? "selected=\"selected\"" : "" ?> value="<?= $method['paymentID'] ?>"><?= $method['name'] ?></option>
												<?php
											}
											?>
										</select>
										
										<input type="hidden" name="orderPaymentID[]" id="orderPaymentID_<?= $payment['orderPaymentID'] ?>" value="<?= $payment['orderPaymentID'] ?>" />
									</td>
									<td>
										<input type="text" name="amount[]" id="amount_<?= $payment['orderPaymentID'] ?>" value="<?= $payment['amount'] ?>" class="width-75" icon="fa-euro" validation-type="int" validation-required="true" />
									</td>
									<td>
										<input type="text" name="date[]" id="date_<?= $payment['orderPaymentID'] ?>" value="<?= $payment['date'] ?>" class="width-100 datepicker" icon="fa-calendar" />
									</td>
									<td>
										<span class="remove-row fa fa-remove" post="/library/php/posts/verkoop/verwijder_betaling.php?itemID=<?= $payment['orderPaymentID'] ?>&returnURL=<?= "/" . _LANGUAGE_PACK . "/modules/" . $_GET['module'] . "/" . $_GET['file'] . "/" . $_GET['form'] . "/" . $_GET['dataID'] ?>"></span>
									</td>
								</tr>
								<?php
							}
						}
						?>
						
						<tr class="new-row">
							<td>
								<select name="paymentID[]" id="paymentID_+" class="width-150">
									<option value=""></option>
									
									<?php
									foreach($data_payments AS $method)
									{
										?>
										<option value="<?= $method['paymentID'] ?>"><?= $method['name'] ?></option>
										<?php
									}
									?>
								</select>
							</td>
							<td><input type="text" name="amount[]" id="amount_+" value="" class="width-75" icon="fa-euro" validation-type="int" validation-required="true" /></td>
							<td><input type="text" name="date[]" id="date_+" value="" class="width-100 datepicker" icon="fa-calendar" /></td>
							<td>&nbsp;</td>
						</tr>
					</tbody>
				</table>
				
				<?php
				if(($data['grand_total'] - $data['payed']) > 0)
				{
					?>
					<br/>
					<a href="<?= $merchant['website_url'] ?>paylink/<?= base64_encode($data['orderID']) ?>" target="_blank">
						<img src="/library/media/betaallink.png" />
					</a>
					<?php
				}
				?>
			</div>
		</div>
		
		<div class="tab tab-5">
			<div class="form-content">
				<table class="form-table">
					<thead>
						<tr>
							<td width="250"><?= $mb->_translateReturn("table-headers", "title") ?></td>
							<td><?= $mb->_translateReturn("table-headers", "content") ?></td>
						</tr>
					</thead>
					
					<tbody>
						<?php
						for($i = 1; $i <= 4; $i++)
						{
							?>
							<tr>
								<td>
									<select name="key_<?= $i ?>" id="key_<?= $i ?>" class="width-200">
										<option value=""></option>
										<option <?= $data['invoice_rules'][$i-1]['key'] == "Aanbetaling" ? "selected=\"selected\"" : "" ?> value="Aanbetaling">Aanbetaling</option>
										<option <?= $data['invoice_rules'][$i-1]['key'] == "Accessoires" ? "selected=\"selected\"" : "" ?> value="Accessoires">Accessoires</option>
										<option <?= $data['invoice_rules'][$i-1]['key'] == "Afhaalmoment" ? "selected=\"selected\"" : "" ?> value="Afhaalmoment">Afhaalmoment</option>
										<option <?= $data['invoice_rules'][$i-1]['key'] == "Afhaalpunt" ? "selected=\"selected\"" : "" ?> value="Afhaalpunt">Afhaalpunt</option>
										<option <?= $data['invoice_rules'][$i-1]['key'] == "Betaling" ? "selected=\"selected\"" : "" ?> value="Betaling">Betaling</option>
										<option <?= $data['invoice_rules'][$i-1]['key'] == "Extra informatie" ? "selected=\"selected\"" : "" ?> value="Extra informatie">Extra informatie</option>
										<option <?= $data['invoice_rules'][$i-1]['key'] == "Extra's" ? "selected=\"selected\"" : "" ?> value="Extra's">Extra's</option>
										<option <?= $data['invoice_rules'][$i-1]['key'] == "Framenummer" ? "selected=\"selected\"" : "" ?> value="Framenummer">Framenummer</option>
										<option <?= $data['invoice_rules'][$i-1]['key'] == "Onderdelen" ? "selected=\"selected\"" : "" ?> value="Onderdelen">Onderdelen</option>
										<option <?= $data['invoice_rules'][$i-1]['key'] == "Opmerkingen" ? "selected=\"selected\"" : "" ?> value="Opmerkingen">Opmerkingen</option>
										<option <?= $data['invoice_rules'][$i-1]['key'] == "Overigen" ? "selected=\"selected\"" : "" ?> value="Overigen">Overigen</option>
										<option <?= $data['invoice_rules'][$i-1]['key'] == "Sleutelnummer" ? "selected=\"selected\"" : "" ?> value="Sleutelnummer">Sleutelnummer</option>
										<option <?= $data['invoice_rules'][$i-1]['key'] == "Ten behoeve van" ? "selected=\"selected\"" : "" ?> value="Ten behoeve van">Ten behoeve van</option>
										<option <?= $data['invoice_rules'][$i-1]['key'] == "Voorwaarden" ? "selected=\"selected\"" : "" ?> value="Voorwaarden">Voorwaarden</option>
									</select>
								</td>
								<td><input type="text" name="value_<?= $i ?>" id="value_<?= $i ?>" value="<?= $data['invoice_rules'][$i-1]['value'] ?>" class="width-300" /></td>
							</tr>
							<?php
						}
						?>
					</tbody>
				</table>
			</div>
		</div>
		
		<div class="tab tab-6 js-load-notes" orderID="<?= intval($_GET['dataID']) ?>">
			<div class="form-content">
				<div class="content-header">
					<span class="fa fa-pencil-square-o"></span>
					<?= $mb->_translateReturn("forms", "legend-notes") ?>
				</div>
				
				<textarea name="note" id="note" class="width-100-percent margin"></textarea>
				<input type="button" name="save_note" id="save_note" value="<?= $mb->_translateReturn("forms", "form-customers-button-save-note") ?>" class="red show-load" onclick="saveCustomerNotes()" />
			</div>
		</div>
		
		<?php
		if($data['customer']['email_address'] != "")
		{
			?>
			<div class="tab tab-7 js-load-emails" e-mail-customer-id="<?= $data['customerID'] ?>">
				<div class="form-content">
					<div class="content-header">
						<span class="fa fa-pencil-square-o"></span>
						<?= $mb->_translateReturn("forms", "legend-new-email") ?>
					</div>
					
					<input type="hidden" name="email_orderID" id="email_orderID" value="<?= $_GET['dataID'] ?>" />
					<input type="hidden" name="email_receiver" id="email_receiver" value="<?= $data['customer']['email_address'] ?>" />
					
					<input type="text" name="email_sender" id="email_sender" value="" class="width-200 double-margin" holder="<?= $mb->_translateReturn("forms", "form-customers-email-sender") ?>" />
					
					<select name="email_template" id="email_template" class="width-300 margin email-template-choice" holder="<?= $mb->_translateReturn("forms", "form-customers-email-template") ?>">
						<option value=""></option>
						
						<?php
						$email_templates = $mb->_runFunction("cms", "viewEmail", array($_SESSION['merchantID'], "", "template_email.name", "0,50", 6));
						
						foreach($email_templates AS $template)
						{
							?>
							<option value="<?= $template['emailID'] ?>"><?= $template['name'] ?></option>
							<?php
						}
						?>
					</select>
					
					<select name="email_attachment" id="email_attachment" class="width-100 double-margin email-template-choice" holder="<?= $mb->_translateReturn("forms", "form-customers-email-attachment") ?>">
						<option value=""></option>
						<option value="invoice">Factuur</option>
						<option value="tender">Offerte</option>
						<option value="picklist">Pakbon</option>
					</select>
					
					<input type="text" name="email_subject" id="email_subject" value="" class="width-300 margin" holder="<?= $mb->_translateReturn("forms", "form-customers-email-subject") ?>" />
					<textarea name="email_content" id="email_content" class="width-100-percent margin" holder="<?= $mb->_translateReturn("forms", "form-customers-email-content") ?>" holder-eg="<?= $mb->_translateReturn("forms", "form-customers-email-content-eg") ?>"></textarea>
					
					<input type="button" name="send_email" id="send_email" value="<?= $mb->_translateReturn("forms", "form-customers-button-send-email") ?>" class="red show-load" onclick="sendEmail()" />
				</div>
			</div>
			<?php
		}
		
		if($data['customer']['mobile_phone'] != "")
		{
			?>
			<div class="tab tab-<?= $data['customer']['email_address'] != "" ? 8 : 7 ?> js-load-sms" sms-customer-id="<?= $data['customerID'] ?>">
				<div class="form-content">
					<div class="content-header">
						<span class="fa fa-pencil-square-o"></span>
						<?= $mb->_translateReturn("forms", "legend-new-sms") ?>
					</div>
					
					<input type="hidden" name="sms_orderID" id="sms_orderID" value="<?= $_GET['dataID'] ?>" />
					<input type="hidden" name="sms_receiver" id="sms_receiver" value="<?= $data['customer']['mobile_phone'] ?>" />
					
					<select name="sms_template" id="sms_template" class="width-300 double-margin sms-template-choice" holder="<?= $mb->_translateReturn("forms", "form-customers-sms-template") ?>">
						<option value=""></option>
						
						<?php
						$sms_templates = $mb->_runFunction("cms", "viewSms", array($_SESSION['merchantID'], "", "template_sms.name", "0,50", 5));
						
						foreach($sms_templates AS $template)
						{
							?>
							<option value="<?= $template['smsID'] ?>"><?= $template['name'] ?></option>
							<?php
						}
						?>
					</select>
					
					<textarea name="sms_content" id="sms_content" class="width-100-percent margin" holder="<?= $mb->_translateReturn("forms", "form-customers-sms-content") ?>" holder-eg="<?= $mb->_translateReturn("forms", "form-customers-sms-content-eg") ?>" max-characters="160"></textarea>
					
					<input type="button" name="send_sms" id="send_sms" value="<?= $mb->_translateReturn("forms", "form-customers-button-send-sms") ?>" class="red show-load" onclick="sendSms()" />
				</div>
			</div>
			<?php
		}
		?>
	</div>
</form>
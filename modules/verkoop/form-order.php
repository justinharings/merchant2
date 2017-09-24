<?php
$mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "SET_BP", 1));

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
?>

<ul class="breadcrumbs">
	<li>Merchant</li>
	<li><?= $mb->_translateReturn("menu", "sales") ?></li>
	<li><?= (isset($_GET['dataID']) ? "Bestelling #" . $data['order_reference'] : $mb->_translateReturn("forms", "add")) ?></li>
</ul>

<form method="post" id="form" action="/library/php/posts/verkoop/save.php">
	<input type="hidden" name="orderID" id="orderID" value="<?= isset($_GET['dataID']) ? $_GET['dataID'] : 0 ?>" />
	<input type="hidden" name="customerID" id="customerID" value="<?= isset($_GET['dataID']) ? $data['customerID'] : 0 ?>" />
	<input type="hidden" name="returnURL" id="returnURL" value="<?= "/" . _LANGUAGE_PACK . "/modules/" . $_GET['module'] . "/" . $_GET['file'] ?>" />
	
	<div class="simple-form">
		<div class="form-header">
			<h1><?= (isset($_GET['dataID']) ? $mb->_translateReturn("forms", "edit", array("Bestelling " . $data['order_reference'])) : $mb->_translateReturn("forms", "add-new-title")) ?></h1>
			
			<input type="button" name="return" id="return" value="<?= $mb->_translateReturn("forms", "button-cancel") ?>" class="show-load" />
			
			<?php
			if(isset($_GET['dataID']))
			{
				?>
				<input type="button" name="delete-item" id="delete-item" value="<?= $mb->_translateReturn("forms", "button-delete") ?>" class="white show-load" />
				<?php
			}
			?>
			
			<input type="submit" name="save" id="save" value="<?= $mb->_translateReturn("forms", "button-save") ?>" class="red show-load validate-form" />
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
						<td style="padding: 0px 20px 0px 0px;">
							<strong><?= $mb->_translateReturn("forms", "form-orders-orderid") ?></strong><br/>
							# <?= $data['order_reference'] ?>
						</td>
						
						<td style="padding: 0px 20px 0px 0px;">
							<strong><?= $mb->_translateReturn("forms", "form-orders-products") ?></strong><br/>
							<?= count($data['products']) ?> <?= $mb->_translateReturn("forms", "form-orders-products-inline") ?>
						</td>
						
						<td style="padding: 0px 20px 0px 0px;">
							<strong><?= $mb->_translateReturn("forms", "form-orders-orderdate") ?></strong><br/>
							<?= $data['date_added'] ?>
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
						<td style="padding: 0px 50px 0px 0px;">
							<a href="/<?= $_SESSION['language_pack'] ?>/modules/klanten/view/form-klant/<?= $data['customer']['customerID'] ?>/"><?= $data['customer']['name'] ?></a><br/>
							<?= $data['customer']['address'] ?><br/>
							<?= $data['customer']['zip_code'] ?> <?= $data['customer']['city'] ?><br/>
							<?= $data['customer']['country'] ?><br/>
							<br/>
							<?php
							if($data['customer']['email_address'] != "")
							{
								?>
								<a href="#" class="activate-tab-6"><?= $data['customer']['email_address'] ?></a><br/>
								<?php
							}
							else
							{
								print "Geen e-mail adres.<br/>";
							}
							
							if($data['customer']['phone'] != "")
							{
								print "Tel: ". $data['customer']['phone'];
							}
							else
							{
								print "Geen telefoonnummer.";
							}
							?>
						</td>
						
						<td valign="top" width="200">
							<?php
							if($data['customer']['count_orders'] > 0)
							{
								?>
								Er is <strong><?= $data['customer']['count_orders'] ?> andere bestelling(en)</strong> met een
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
						</td>
					</tr>
				</table>
			</div>
		</div>
		
		<div class="tab tab-2">
			<div class="form-content">
				<div class="content-header">
					<span class="fa fa-pencil-square-o"></span>
					<?= $mb->_translateReturn("forms", "legend-placed-orders") ?>
				</div>
			</div>
		</div>
		
		<div class="tab tab-3">
			<div class="form-content">
				<div class="content-header">
					<span class="fa fa-pencil-square-o"></span>
					<?= $mb->_translateReturn("forms", "legend-placed-orders") ?>
				</div>
			</div>
		</div>
		
		<div class="tab tab-4">
			<div class="form-content">
				<div class="content-header">
					<span class="fa fa-pencil-square-o"></span>
					<?= $mb->_translateReturn("forms", "legend-workorder-history") ?>
				</div>
			</div>
		</div>
		
		<div class="tab tab-5">
			<div class="form-content">
				<div class="content-header">
					<span class="fa fa-pencil-square-o"></span>
					<?= $mb->_translateReturn("forms", "legend-google-map") ?>
				</div>
				
				<iframe width="100%" style="height: 600px;" frameborder="0" src="https://www.google.com/maps/embed/v1/place?q=<?= $data['address'] ?>%20<?= $data['zip_code'] ?>%20<?= $data['city'] ?>&amp;key=AIzaSyANFoo0c9vIl4a6vKF5JaCv7Vzqwdx36Yg" allowfullscreen=""></iframe>
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
			<div class="tab tab-7 js-load-emails" e-mail-customer-id="<?= $_GET['dataID'] ?>">
				<div class="form-content">
					<div class="content-header">
						<span class="fa fa-pencil-square-o"></span>
						<?= $mb->_translateReturn("forms", "legend-new-email") ?>
					</div>
					
					<input type="hidden" name="email_customerID" id="email_customerID" value="<?= $_GET['dataID'] ?>" />
					<input type="hidden" name="email_receiver" id="email_receiver" value="<?= $data['email_address'] ?>" />
					
					<input type="text" name="email_sender" id="email_sender" value="<?= $merchant['email_address'] ?>" class="width-200 double-margin" holder="<?= $mb->_translateReturn("forms", "form-customers-email-sender") ?>" />
					<input type="text" name="email_subject" id="email_subject" value="" class="width-300 margin" holder="<?= $mb->_translateReturn("forms", "form-customers-email-subject") ?>" />
					<textarea name="email_content" id="email_content" class="width-100-percent margin" holder="<?= $mb->_translateReturn("forms", "form-customers-email-content") ?>" holder-eg="<?= $mb->_translateReturn("forms", "form-customers-email-content-eg") ?>"></textarea>
					
					<input type="button" name="send_email" id="send_email" value="<?= $mb->_translateReturn("forms", "form-customers-button-send-email") ?>" class="red show-load" onclick="sendEmail()" />
				</div>
			</div>
			<?php
		}
		?>
	</div>
</form>
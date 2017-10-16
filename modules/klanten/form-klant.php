<?php
$mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "SET_BP", 1));

if(isset($_GET['dataID']))
{
	$data = $mb->_runFunction("customers", "load", array($_GET['dataID']));
	$merchant = $mb->_runFunction("merchant", "load", array($_SESSION['merchantID']));
}
?>

<ul class="breadcrumbs">
	<li>Merchant</li>
	<li><?= $mb->_translateReturn("menu", "settings") ?></li>
	<li><?= $mb->_translateReturn("menu", "customers") ?></li>
	<li><?= (isset($_GET['dataID']) ? $data['name'] : $mb->_translateReturn("forms", "add")) ?></li>
</ul>

<form method="post" id="form" action="/library/php/posts/klanten/klant.php">
	<input type="hidden" name="customerID" id="customerID" value="<?= isset($_GET['dataID']) ? $_GET['dataID'] : 0 ?>" />
	<input type="hidden" name="returnURL" id="returnURL" value="<?= "/" . _LANGUAGE_PACK . "/modules/" . $_GET['module'] . "/" . $_GET['file'] . "/" . $_GET['form'] . "/[dataID]/" ?>" />
	
	<div class="simple-form">
		<div class="form-header">
			<h1><?= (isset($_GET['dataID']) ? $mb->_translateReturn("forms", "edit", array($data['name'])) : $mb->_translateReturn("forms", "add-new-title")) ?></h1>
			
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
		
		<?php
		if(isset($_GET['dataID']))
		{
			?>
			<div class="form-tabs">
				<div class="fa fa-bars"></div>
				
				<div class="fa fa-pencil"></div>
				<div class="fa fa-sticky-note"></div>
				<div class="fa fa-shopping-bag smaller"></div>
				<div class="fa fa-wrench"></div>
				<div class="fa fa-map"></div>
				
				<?php
				if($data['email_address'] != "")
				{
					?>
					<div class="fa fa-at"></div>
					<?php
				}
				?>
			</div>
			<?php
		}
		?>
		
		<div class="tab tab-1">
			<div class="form-content">
				<div class="content-header">
					<span class="fa fa-pencil-square-o"></span>
					<?= $mb->_translateReturn("forms", "legend-personal-information") ?>
				</div>
				
				<input type="text" name="name" id="name" value="<?= isset($_GET['dataID']) ? $data['name'] : "" ?>" class="width-300 margin" holder="<?= $mb->_translateReturn("forms", "form-customers-name") ?>" validation-required="true" validation-type="text" />
				
				<input type="text" name="company" id="company" value="<?= isset($_GET['dataID']) ? $data['company'] : "" ?>" class="width-300" holder="<?= $mb->_translateReturn("forms", "form-customers-company") ?>" holder-eg="<?= $mb->_translateReturn("forms", "form-customers-company-eg") ?>" />
			</div>
			
			<div class="form-content">
				<div class="content-header">
					<span class="fa fa-pencil-square-o"></span>
					<?= $mb->_translateReturn("forms", "legend-customer-card") ?>
				</div>
				
				<input type="text" name="customer_code" id="customer_code" value="<?= isset($_GET['dataID']) ? $data['customer_code'] : "" ?>" class="width-300" />
			</div>
			
			<div class="form-content">
				<div class="content-header">
					<span class="fa fa-pencil-square-o"></span>
					<?= $mb->_translateReturn("forms", "legend-address-information") ?>
				</div>
				
				<input type="text" name="address" id="address" value="<?= isset($_GET['dataID']) ? $data['address'] : "" ?>" class="width-300 margin" holder="<?= $mb->_translateReturn("forms", "form-customers-address") ?>" holder-eg="<?= $mb->_translateReturn("forms", "form-customers-address-eg") ?>" validation-required="true" validation-type="text" />
				<input type="text" name="zip_code" id="zip_code" value="<?= isset($_GET['dataID']) ? $data['zip_code'] : "" ?>" class="width-100 margin" holder="<?= $mb->_translateReturn("forms", "form-customers-zipcode") ?>" validation-required="true" validation-type="text" />
				<input type="text" name="city" id="city" value="<?= isset($_GET['dataID']) ? $data['city'] : "" ?>" class="width-300 margin" holder="<?= $mb->_translateReturn("forms", "form-customers-city") ?>" validation-required="true" validation-type="text" />
				
				<select name="country" id="country" class="width-300" holder="<?= $mb->_translateReturn("forms", "form-customers-country") ?>">
					<?php
					$_countries = $mb->_allCountries();
					
					foreach($_countries AS $value)
					{
						?>
						<option <?= (isset($_GET['dataID']) && $data['country'] == $value) || (!isset($_GET['dataID']) && $value == "Netherlands") ? "selected=\"selected\"" : "" ?> value="<?= $value ?>"><?= $value ?></option>
						<?php
					}
					?>
				</select>
			</div>
			
			<div class="form-content">
				<div class="content-header">
					<span class="fa fa-pencil-square-o"></span>
					<?= $mb->_translateReturn("forms", "legend-contact-information") ?>
				</div>
				
				<input type="text" name="phone" id="phone" value="<?= isset($_GET['dataID']) ? $data['phone'] : "" ?>" class="width-300 margin" holder="<?= $mb->_translateReturn("forms", "form-customers-phone") ?>" />
				<input type="text" name="mobile_phone" id="mobile_phone" value="<?= isset($_GET['dataID']) ? $data['mobile_phone'] : "" ?>" class="width-300 margin" holder="<?= $mb->_translateReturn("forms", "form-customers-mobile-phone") ?>" />
				<input type="text" name="email_address" id="email_address" value="<?= isset($_GET['dataID']) ? $data['email_address'] : "" ?>" class="width-300" holder="<?= $mb->_translateReturn("forms", "form-customers-email") ?>" validation-type="email" />
			</div>
		</div>
		
		<?php
		if(isset($_GET['dataID']))
		{
			?>
			<div class="tab tab-2 js-load-notes">
				<div class="form-content">
					<div class="content-header">
						<span class="fa fa-pencil-square-o"></span>
						<?= $mb->_translateReturn("forms", "legend-notes") ?>
					</div>
					
					<textarea name="note" id="note" class="width-100-percent margin"></textarea>
					<input type="button" name="save_note" id="save_note" value="<?= $mb->_translateReturn("forms", "form-customers-button-save-note") ?>" class="red show-load" onclick="saveCustomerNotes()" />
				</div>
			</div>
			
			<div class="tab tab-3">
				<div class="form-content">
					<table class="form-table">
						<thead>
							<tr>
								<td><?= $mb->_translateReturn("table-headers", "orderid") ?></td>
								<td><?= $mb->_translateReturn("table-headers", "date") ?></td>
								<td><?= $mb->_translateReturn("table-headers", "grand_total") ?></td>
								<td><?= $mb->_translateReturn("table-headers", "status") ?></td>
							</tr>
						</thead>
						
						<tbody>
							<?php
							if(count($data['orders']) > 0)
							{
								foreach($data['orders'] AS $order)
								{
									?>
									<tr>
										<td>
											<a href="/<?= $_SESSION['_LANGUAGE_PACK'] ?>/modules/verkoop/openstaand/form-order/<?= $order['orderID'] ?>/">
												<?= $order['order_reference'] ?>
											</a>
										</td>
										<td><?= $order['date_added'] ?></td>
										<td>&euro;&nbsp;<?= _frontend_float($order['grand_total']) ?></td>
										<td><?= $order['status'] ?></td>
									</tr>
									<?php
								}
							}
							else
							{
								?>
								<tr>
									<td colspan="4" align="center"><?= $mb->_translateReturn("table-headers", "no-results") ?></td>
								</tr>
								<?php
							}
							?>
						</tbody>
					</table>
				</div>
			</div>
			
			<div class="tab tab-4">
				<div class="form-content">
					<div class="content-header">
						<span class="fa fa-pencil-square-o"></span>
						<?= $mb->_translateReturn("forms", "legend-workorder-history") ?>
					</div>
					
					<table class="form-table">
						<thead>
							<tr>
								<td><?= $mb->_translateReturn("table-headers", "workorderid") ?></td>
								<td><?= $mb->_translateReturn("table-headers", "status") ?></td>
								<td><?= $mb->_translateReturn("table-headers", "expiration_date") ?></td>
								<td><?= $mb->_translateReturn("table-headers", "grand_total") ?></td>
								<td><?= $mb->_translateReturn("table-headers", "employee") ?></td>
							</tr>
						</thead>
						
						<tbody>
							<?php
							if(count($data['workorders']) > 0)
							{
								foreach($data['workorders'] AS $workorder)
								{
									?>
									<tr>
										<td><?= $workorder['workorderID'] ?></td>
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
										</td>
										<td><?= $workorder['expiration_date'] ?></td>
										<td>&euro;&nbsp;<?= _frontend_float($workorder['grand_total']) ?></td>
										<td><?= ($workorder['employee'] != "" ? $workorder['employee'] : "-") ?></td>
									</tr>
									<?php
								}
							}
							else
							{
								?>
								<tr>
									<td colspan="5" align="center"><?= $mb->_translateReturn("table-headers", "no-results") ?></td>
								</tr>
								<?php
							}
							?>
						</tbody>
					</table>
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
			
			<?php
			if($data['email_address'] != "")
			{
				?>
				<div class="tab tab-6 js-load-emails" e-mail-customer-id="<?= $_GET['dataID'] ?>">
					<div class="form-content">
						<div class="content-header">
							<span class="fa fa-pencil-square-o"></span>
							<?= $mb->_translateReturn("forms", "legend-new-email") ?>
						</div>
						
						<input type="hidden" name="email_customerID" id="email_customerID" value="<?= $_GET['dataID'] ?>" />
						<input type="hidden" name="email_receiver" id="email_receiver" value="<?= $data['email_address'] ?>" />
						
						<input type="text" name="email_sender" id="email_sender" value="" class="width-200 double-margin" holder="<?= $mb->_translateReturn("forms", "form-customers-email-sender") ?>" />
						
						<select name="email_template" id="email_template" class="width-300 double-margin email-template-choice" holder="<?= $mb->_translateReturn("forms", "form-customers-email-template") ?>">
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
						
						<input type="text" name="email_subject" id="email_subject" value="" class="width-300 margin" holder="<?= $mb->_translateReturn("forms", "form-customers-email-subject") ?>" />
						<textarea name="email_content" id="email_content" class="width-100-percent margin" holder="<?= $mb->_translateReturn("forms", "form-customers-email-content") ?>" holder-eg="<?= $mb->_translateReturn("forms", "form-customers-email-content-eg") ?>"></textarea>
						
						<input type="button" name="send_email" id="send_email" value="<?= $mb->_translateReturn("forms", "form-customers-button-send-email") ?>" class="red show-load" onclick="sendEmail()" />
					</div>
				</div>
				<?php
			}
		}
		?>
	</div>
</form>
<?php
$mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "SET_BP", 1));

if(isset($_GET['dataID']))
{
	$data = $mb->_runFunction("paylink", "load", array($_GET['dataID']));
}

$data_payments = $mb->_runFunction("payment_methods", "view", array($_SESSION['merchantID'], "", "payment_methods.name", "0,50"));
?>

<ul class="breadcrumbs">
	<li>Merchant</li>
	<li><?= $mb->_translateReturn("menu", "sales") ?></li>
	<li><?= $mb->_translateReturn("menu", "paylink") ?></li>
	<li><?= (isset($_GET['dataID']) ? $data['description'] : $mb->_translateReturn("forms", "add")) ?></li>
</ul>

<form method="post" id="form" action="/library/php/posts/verkoop/paylink.php">
	<input type="hidden" name="paylinkID" id="paylinkID" value="<?= isset($_GET['dataID']) ? $_GET['dataID'] : 0 ?>" />
	<input type="hidden" name="returnURL" id="returnURL" value="<?= "/" . _LANGUAGE_PACK . "/modules/" . $_GET['module'] . "/" . $_GET['file'] ?>" />
	
	<div class="simple-form">
		<div class="form-header">
			<h1><?= (isset($_GET['dataID']) ? $mb->_translateReturn("forms", "edit", array($data['description'])) : $mb->_translateReturn("forms", "add-new-title")) ?></h1>
			
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
		
		<div class="form-content">
			<div class="content-header">
				<span class="fa fa-pencil-square-o"></span>
				<?= $mb->_translateReturn("forms", "legend-general") ?>
			</div>
			
			<input type="text" name="description" id="description" value="<?= isset($_GET['dataID']) ? $data['description'] : "" ?>" class="width-300 margin" holder="<?= $mb->_translateReturn("forms", "form-paylink-description") ?>" holder-eg="<?= $mb->_translateReturn("forms", "form-paylink-description-eg") ?>" validation-required="true" validation-type="text" />
			<input type="text" name="orderID" id="orderID" value="<?= isset($_GET['dataID']) ? $data['orderID'] : "" ?>" class="width-200" holder="<?= $mb->_translateReturn("forms", "form-paylink-orderid") ?>" holder-eg="<?= $mb->_translateReturn("forms", "form-paylink-orderid-eg") ?>" validation-required="true" validation-type="int" />
		</div>
		
		<div class="form-content">
			<div class="content-header">
				<span class="fa fa-pencil-square-o"></span>
				<?= $mb->_translateReturn("forms", "legend-payment-module-required") ?>
			</div>
			
			<select name="paymentID" id="paymentID" class="width-200 margin" holder="<?= $mb->_translateReturn("forms", "form-paylink-payment-method") ?>">
				<option value=""></option>
				
				<?php
				foreach($data_payments AS $method)
				{
					if($method['module'] == "")
					{
						continue;
					}
					?>
					
					<option <?= $method['paymentID'] == $data['paymentID'] ? "selected=\"selected\"" : "" ?> value="<?= $method['paymentID'] ?>"><?= $method['name'] ?></option>
					
					<?php
				}
				?>
			</select>
			
			<input type="text" name="amount" id="amount" value="<?= isset($_GET['dataID']) ? $data['amount'] : "" ?>" class="width-100 margin" holder="<?= $mb->_translateReturn("forms", "form-paylink-amount") ?>" icon="fa-euro" validation-required="true" validation-type="int" />
		</div>
	</div>
</form>
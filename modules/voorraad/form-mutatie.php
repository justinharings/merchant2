<?php
$mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "SET_MB", 1));

if(isset($_GET['dataID']))
{
	$data = $mb->_runFunction("products", "load", array($_GET['dataID']));
}
?>

<ul class="breadcrumbs">
	<li>Merchant</li>
	<li><?= $mb->_translateReturn("menu", "stock") ?></li>
	<li><?= $mb->_translateReturn("menu", "stock-mutations") ?></li>
	<li><?= (isset($_GET['dataID']) ? $data['name'] : "") ?></li>
</ul>

<form method="post" id="form" action="/library/php/posts/voorraad/mutatie.php">
	<input type="hidden" name="productID" id="productID" value="<?= isset($_GET['dataID']) ? $_GET['dataID'] : 0 ?>" />
	<input type="hidden" name="returnURL" id="returnURL" value="<?= "/" . _LANGUAGE_PACK . "/modules/" . $_GET['module'] . "/" . $_GET['file'] ?>" />
	
	<div class="simple-form">
		<div class="form-header">
			<h1><?= (isset($_GET['dataID']) ? $data['name'] : "") ?></h1>
			
			<input type="button" name="return" id="return" value="<?= $mb->_translateReturn("forms", "button-cancel") ?>" class="show-load" />
			<input type="submit" name="save" id="save" value="<?= $mb->_translateReturn("forms", "button-save") ?>" class="red show-load validate-form" />
		</div>
		
		<?php
		$data_locations = $mb->_runFunction("stock", "viewLocations", array($_SESSION['merchantID'], "", "locations.name", "0,50"));
		
		foreach($data_locations AS $location)
		{
			$stock = $mb->_runFunction("stock", "getStock", array($_GET['dataID'], $location['locationID']));
			?>
			
			<div class="form-content">
				<div class="content-header">
					<span class="fa fa-pencil-square-o"></span>
					<?= $mb->_translateReturn("forms", "legend-stocks") ?> &#187; <?= $location['name'] ?>
				</div>
				
				<table>
					<tr>
						<td width="130">Voorraad:</td>
						<td><?= $stock['stock'] ?> <?= $mb->_translateReturn("forms", "legend-stocks-inline") ?></td>
					</tr>
					
					<tr>
						<td>Economisch:</td>
						<td>0 <?= $mb->_translateReturn("forms", "legend-stocks-inline") ?></td>
					</tr>
					
					<tr>
						<td><strong>Gereserveerd:</strong></td>
						<td>1 <?= $mb->_translateReturn("forms", "legend-stocks-inline") ?></td>
					</tr>
				</table>
			</div>
			
			<div class="form-content">
				<div class="content-header">
					<span class="fa fa-pencil-square-o"></span>
					<?= $mb->_translateReturn("forms", "legend-stock-mutation") ?> &#187; <?= $location['name'] ?>
				</div>
				
				<input type="hidden" name="stock_location[]" id="stock_location_<?= $location['locationID'] ?>" value="<?= $location['locationID'] ?>" />
				<input type="text" name="stock_mutation[]" id="stock_mutation_<?= $location['locationID'] ?>" class="width-250" value="" holder="<?= $mb->_translateReturn("forms", "form-products-mutation") ?>" holder-eg="<?= $mb->_translateReturn("forms", "form-products-mutation-eg") ?>" validation-required="true" validation-type="int" />
			</div>
			<?php
		}
		?>
	</div>
</form>
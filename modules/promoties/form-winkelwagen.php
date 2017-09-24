<?php
$mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "SET_BP", 1));

if(isset($_GET['dataID']))
{
	$data = $mb->_runFunction("promotions", "load", array($_GET['dataID']));
}
?>

<ul class="breadcrumbs">
	<li>Merchant</li>
	<li><?= $mb->_translateReturn("menu", "promotions") ?></li>
	<li><?= $mb->_translateReturn("menu", "cart-discounts") ?></li>
	<li><?= (isset($_GET['dataID']) ? $data['name'] : $mb->_translateReturn("forms", "add")) ?></li>
</ul>

<form method="post" id="form" action="/library/php/posts/promoties/cart.php">
	<input type="hidden" name="promotionID" id="promotionID" value="<?= isset($_GET['dataID']) ? $_GET['dataID'] : 0 ?>" />
	<input type="hidden" name="returnURL" id="returnURL" value="<?= "/" . _LANGUAGE_PACK . "/modules/" . $_GET['module'] . "/" . $_GET['file'] ?>" />
	
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
		
		<div class="form-content">
			<div class="content-header">
				<span class="fa fa-pencil-square-o"></span>
				<?= $mb->_translateReturn("forms", "legend-general") ?>
			</div>
			
			<input type="text" name="name" id="name" value="<?= isset($_GET['dataID']) ? $data['name'] : "" ?>" class="width-300 double-margin" holder="<?= $mb->_translateReturn("forms", "form-promotions-name") ?>" holder-eg="<?= $mb->_translateReturn("forms", "form-promotions-name-eg") ?>" validation-required="true" validation-type="text" />
			<input type="text" name="date_from" id="date_from" value="<?= isset($_GET['dataID']) ? $data['date_from'] : "" ?>" class="width-100 margin datepicker" icon="fa-calendar" holder="<?= $mb->_translateReturn("forms", "form-promotions-date-from") ?>" validation-required="true" validation-type="text" />
			<input type="text" name="date_to" id="date_to" value="<?= isset($_GET['dataID']) ? $data['date_to'] : "" ?>" class="width-100 margin datepicker" icon="fa-calendar" holder="<?= $mb->_translateReturn("forms", "form-promotions-date-to") ?>" validation-required="true" validation-type="text" />
		</div>
		
		<div class="form-content">
			<div class="content-header">
				<span class="fa fa-pencil-square-o"></span>
				<?= $mb->_translateReturn("forms", "legend-products") ?>
			</div>
		
			<table class="form-table">
				<thead>
					<tr>
						<td><?= $mb->_translateReturn("forms", "form-promotions-table-product") ?></td>
						<td><?= $mb->_translateReturn("forms", "form-promotions-table-price") ?></td>
						<td><?= $mb->_translateReturn("forms", "form-promotions-table-type") ?></td>
						<td><?= $mb->_translateReturn("forms", "form-promotions-table-dicount") ?></td>
						<td width="1"><span class="add-row fa fa-plus-circle"></span></td>
					</tr>
				</thead>
				
				<tbody>
					<?php
					foreach($data['products'] AS $value)
					{
						?>
						<tr>
							<td><?= $value['name'] ?></td>
							<td>&euro;&nbsp;<?= _frontend_float($value['price']) ?></td>
							<td><?= ($value['discount_type'] == 1 ? "Percentage" : "Vaste prijs") ?></td>
							<td><?= _frontend_float($value['discount']) ?></td>
							<td>
								<?php
								if($value['thumb'] == 0)
								{
									?>
									<span class="remove-row fa fa-remove" post="/library/php/posts/promoties/verwijder_promotie_item.php?itemID=<?= $value['promotionProductID'] ?>&returnURL=<?= "/" . _LANGUAGE_PACK . "/modules/" . $_GET['module'] . "/" . $_GET['file'] . "/" . $_GET['form'] . "/" . $_GET['dataID'] ?>"></span>
									<?php
								}
								?>
							</td>
						</tr>
						<?php
					}
					?>
					
					<tr class="new-row">
						<td class="searched-p-name"><input type="text" name="article_code[]" id="article_code_+" value="" class="width-100 product-search" validation-required="true" validation-type="int" icon="fa-search" /></td>
						<td class="searched-p-price">&nbsp;</td>
						<td>
							<select name="type[]" id="type_+" class="width-150">
								<option value="1"><?= $mb->_translateReturn("forms", "form-promotions-inline-percentage") ?></option>
								<option value="2"><?= $mb->_translateReturn("forms", "form-promotions-inline-fixed") ?></option>
							</select>
						</td>
						<td><input type="text" name="discount[]" id="discount_+" value="" class="width-100" validation-required="true" validation-type="int" /></td>
						<td class="searched-p-productID">&nbsp;</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</form>
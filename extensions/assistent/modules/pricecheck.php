<?php
$query = sprintf(
	"	SELECT		products.*,
					IF(products_pricecheck.website LIKE '%%fietsenwinkel.nl%%', (products_pricecheck.price+30), products_pricecheck.price) AS concurent_price,
					taxes.percentage AS taxrate,
					IF(
						DATE_FORMAT(products_pricecheck.date_update, '%%d-%%m-%%Y @ %%k:%%i') = '00-00-0000 @ 0:00',
						'n.v.t.',
						DATE_FORMAT(products_pricecheck.date_update, '%%d-%%m-%%Y @ %%k:%%i')
					) AS date_pricecheck
		FROM		products
		INNER JOIN	products_pricecheck ON products_pricecheck.productID = products.productID
		INNER JOIN	taxes ON taxes.taxesID = products.taxesID
		WHERE		products.price_purchase != 0
			AND		products.merchantID = 1"	
);
$result = $mb->query($query);

$data = array();

while($row = $mb->fetch_assoc($result))
{
	$data[] = $row;
}
?>

<form method="post" action="/extensions/assistent/library/php/posts/pricecheck.php">
	<div class="view-options">
		<input type="submit" name="save" id="save" value="Wijzigingen opslaan" class="red show-load validate-form" style="float: right;" />
	</div>
	
	<table class="view <?= count($data) ? "hoverable" : "" ?>">
		<thead>
			<tr>
				<td>O / M</td>
				<td>AC</td>
				<td>Naam</td>
				<td>Prijscontrole</td>
				<td>Inkoop (incl.)</td>
				<td>Huidige prijs</td>
				<td>Concurent</td>
				<td>Nieuwe prijs</td>
			</tr>
		</thead>
		
		<tbody>
			<?php
			if(count($data))
			{
				$cnt = 0;
				$lowest = array();
				
				foreach($data AS $key => $value)
				{
					if(isset($lowest[$value['productID']]))
					{
						if($lowest[$value['productID']] > $value['concurent_price'])
						{
							$lowest[$value['productID']] = $value['concurent_price'];
						}
					}
					else
					{
						$lowest[$value['productID']] = $value['concurent_price'];
					}
				}
				
				$skip = array();
				
				foreach($data AS $key => $value)
				{
					if(in_array($value['productID'], $skip))
					{
						continue;
					}
					
					$skip[] = $value['productID'];
					
					$cnt++;
					
					if($cnt == 25)
					{
						return false;
					}
					
					$concurent = $lowest[$value['productID']];
					
					$value['price_purchase'] = $value['price_purchase'] + ($value['price_purchase']/100*$value['taxrate']);
					?>
					<tr>
						<td>
							<?php
							if($concurent > 0)
							{
								?>
								<input type="hidden" name="productIDs[]" id="productIDs_<?= $value['productID'] ?>"  value="<?= $value['productID'] ?>" />
								
								<input type="radio" checked="checked" name="action_<?= $value['productID'] ?>" id="action_<?= $value['productID'] ?>" value="0" style="-webkit-appearance: radio !important;" onclick="$(this).parent().parent().find('td.price').find('input').css('border-color', 'red');" />
								&nbsp;&nbsp;
								<input type="radio" name="action_<?= $value['productID'] ?>" id="action_<?= $value['productID'] ?>" value="1" style="-webkit-appearance: radio !important;" onclick="$(this).parent().parent().find('td.price').find('input').css('border-color', 'green');" />
								<?php
							}
							?>
						</td>
						<td <?= $concurent == 0 ? "style=\"color: #cccccc;\"" : "" ?>><?= $value['article_code'] ?></td>
						<td <?= $concurent == 0 ? "style=\"color: #cccccc;\"" : "" ?>><?= $value['name'] ?></td>
						<td>
							<?php
							if($concurent > 0)
							{
								print $value['date_pricecheck'];
							}
							?>
						</td>
						<td>
							<?php
							if($concurent > 0)
							{
								?>
								&euro;&nbsp;<?= number_format($value['price_purchase'], 2) ?>
								<?php
							}
							?>
						</td>
						<td>
							<?php
							if($concurent > 0)
							{
								if($value['price'] == $concurent)
								{
									print "<span class=\"fa fa-unsorted\" style=\"font-size: 16px; color: orange;\"></span>";
									$color = "orange";
								}
								else
								{
									if($value['price'] > $concurent)
									{
										print "<span class=\"fa fa-caret-up\" style=\"font-size: 16px; color: red;\"></span>";
										$color = "red";
									}
									else if($value['price'] < $concurent)
									{
										print "<span class=\"fa fa-caret-down\" style=\"font-size: 16px; color: green;\"></span>";
										$color = "green";
									}
								}
								?>
								<span style="color: <?= $color ?>">
									&euro;&nbsp;<?= $value['price'] ?>
								</span>
								
								<small>(&euro;&nbsp;<?= number_format($value['price'] - $value['price_purchase'], 2) ?>)</small>
								<?php
							}
							?>
						</td>
						<td>
							<?php
							if($concurent > 0)
							{
								$new_price = $concurent;
									
								if($concurent < ceil($value['price_purchase']))
								{
									$new_price = ceil($value['price_purchase']) + 20;
									print "<span class=\"fa fa-exclamation-triangle\" style=\"color: red;\"></span>";
								}
								?>
								&euro;&nbsp;<?= $concurent ?>&nbsp;<small>(&euro;&nbsp;<?= number_format($concurent - $value['price_purchase'], 2) ?>)</small>
								<?php
							}
							?>
						</td>
						<td class="price">
							<?php
							if($concurent > 0)
							{
								?>
								<input type="text" name="price_<?= $value['productID'] ?>" id="price_<?= $value['productID'] ?>" value="<?= $new_price ?>" class="width-75" icon="fa-euro" style="border-color: red;" />
								<?php
							}
							?>
						</td>
					</tr>
					<?php
				}
			}
			else
			{
				?>
				<tr>
					<td colspan="6" align="center">Er zijn geen artikelen die opgeruimd hoeven te worden.</td>
				</tr>
				<?php
			}
			?>
		</tbody>
	</table>
</form>
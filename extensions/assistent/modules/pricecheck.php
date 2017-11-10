<?php
$query = sprintf(
	"	SELECT		products.*,
					products_pricecheck.price AS concurent_price,
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
				<td>Adviesprijs</td>
				<td>Inkoop (incl.)</td>
				<td>Huidige prijs</td>
				<td>Laagste concurent</td>
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
					if(in_array($row['productID'], $skip))
					{
						continue;
					}
					
					if($value['price'] == $concurent)
					{
						continue;
					}
					
					$skip[] = $row['productID'];
					
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
							<input type="hidden" name="productIDs[]" id="productIDs_<?= $value['productID'] ?>"  value="<?= $value['productID'] ?>" />
							
							<input type="radio" checked="checked" name="action_<?= $value['productID'] ?>" id="action_<?= $value['productID'] ?>" value="0" style="-webkit-appearance: radio !important;" onclick="$(this).parent().parent().find('td.price').find('input').css('border-color', 'red');" />
							&nbsp;&nbsp;
							<input type="radio" name="action_<?= $value['productID'] ?>" id="action_<?= $value['productID'] ?>" value="1" style="-webkit-appearance: radio !important;" onclick="$(this).parent().parent().find('td.price').find('input').css('border-color', 'green');" />
						</td>
						<td><?= $value['article_code'] ?></td>
						<td><?= $value['name'] ?></td>
						<td><?= $value['date_pricecheck'] ?></td>
						<td>&euro;&nbsp;<?= $value['price_adviced'] ?></td>
						<td>&euro;&nbsp;<?= number_format($value['price_purchase'], 2) ?></td>
						<td>
							<?php
							if($value['price'] == ceil($value['price_purchase']))
							{
								print "<span class=\"fa fa-unsorted\" style=\"color: orange;\"></span>";
							}
							else
							{
								if($value['price'] > $concurent)
								{
									print "<span class=\"fa fa-caret-up\" style=\"color: red;\"></span>";
								}
								else if($value['price'] < $concurent)
								{
									print "<span class=\"fa fa-caret-down\" style=\"color: green;\"></span>";
								}
							}
							?>
							&euro;&nbsp;<?= $value['price'] ?>
						</td>
						<td>
							<?php
							$new_price = $concurent;
								
							if($concurent < $value['price_adviced'])
							{
								$new_price = ceil($value['price_purchase']);
								print "<span class=\"fa fa-exclamation-triangle\" style=\"color: red;\"></span>";
							}
							?>
							&euro;&nbsp;<?= $concurent ?>
						</td>
						<td class="price">
							<input type="text" name="price_<?= $value['productID'] ?>" id="price_<?= $value['productID'] ?>" value="<?= $new_price ?>" class="width-75" icon="fa-euro" style="border-color: red;" />
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
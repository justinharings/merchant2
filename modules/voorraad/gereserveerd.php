<?php
$mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "SET_BP", 1));

$data = $mb->_runFunction("stock", "viewReservations", array($_SESSION['merchantID'], "0,250"));
$form = "/form-mutatie/";
?>

<ul class="breadcrumbs">
	<li>Merchant</li>
	<li><?= $mb->_translateReturn("menu", "stock") ?></li>
	<li><?= $mb->_translateReturn("menu", "reserved-articles") ?></li>
</ul>

<div class="view-options">
	<div class="button fa fa-question-circle" title="Wilt u lokaal reserveren op basis van gereserveerde artikelen? Maak dan gebruik van dit overzicht. Heeft u een artikel apart gezet/gelegd? Dan kunt u het rode vinkje omzetten voor uw eigen duidelijkheid."></div>
</div>

<table class="view">
	<thead>
		<tr>
			<td width="80">&nbsp;</td>
			<td><?= $mb->_translateReturn("table-headers", "ac") ?></td>
			<td class="hide-mobile"><?= $mb->_translateReturn("table-headers", "sc") ?></td>
			<td><?= $mb->_translateReturn("table-headers", "quantity") ?></td>
			<td><?= $mb->_translateReturn("table-headers", "product") ?></td>
		</tr>
	</thead>
	
	<tbody>
		<?php
		if($mb->num_rows($data))
		{
			foreach($data AS $value)
			{
				?>
				<tr>
					<td><input type="checkbox" name="checked_<?= $value['productID'] ?>" id="checked_<?= $value['productID'] ?>" value="1" class="no-text" /></td>
					<td><?= $value['article_code'] ?></td>
					<td class="hide-mobile"><?= $value['supplier_code'] ?></td>
					<td><?= $value['quantity'] ?> <?= $mb->_translateReturn("table-headers", "quantity-inline") ?></td>
					<td><?= $value['name'] ?></td>
				</tr>
				<?php
			}
		}
		else
			{
			?>
			<tr>
				<td colspan="6" align="center"><?= $mb->_translateReturn("table-headers", "no-results") ?></td>
			</tr>
			<?php
		}
		?>
	</tbody>
</table>
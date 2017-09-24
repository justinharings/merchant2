<?php
$mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "SET_BP", 1));

$data = $mb->_runFunction("products", "view", array($_SESSION['merchantID'], " ", "stock", "0,250"));
$form = "/form-mutatie/";
?>

<ul class="breadcrumbs">
	<li>Merchant</li>
	<li><?= $mb->_translateReturn("menu", "stock") ?></li>
	<li><?= $mb->_translateReturn("menu", "low-stock") ?></li>
</ul>

<table class="view">
	<thead>
		<tr>
			<td width="80">&nbsp;</td>
			<td><?= $mb->_translateReturn("table-headers", "ac") ?></td>
			<td class="hide-mobile"><?= $mb->_translateReturn("table-headers", "sc") ?></td>
			<td><?= $mb->_translateReturn("table-headers", "product") ?></td>
			<td><?= $mb->_translateReturn("table-headers", "stock") ?></td>
			<td class="hide-mobile"><?= $mb->_translateReturn("table-headers", "status") ?></td>
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
					<td><?= $value['name'] ?></td>
					<td>
						<?= $value['stock'] . " " . $mb->_translateReturn("table-headers", "count-stock-inline") ?>
					</td>
					<td class="hide-mobile"><?= $mb->_runFunction("products", "translateStatus", array($value['status'])) ?></td>
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
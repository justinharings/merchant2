<?php
$mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "TPB_BB", 1));

$data = $mb->_runFunction("categories", "view", array($_SESSION['merchantID'], "", "categories.name", "0,50", 0));
$form = "/form-categorie/";
?>

<ul class="breadcrumbs">
	<li>Merchant</li>
	<li><?= $mb->_translateReturn("menu", "catalog") ?></li>
	<li><?= $mb->_translateReturn("menu", "webshop-categories") ?></li>
</ul>

<div class="view-options">
	<div class="button fa fa-plus-circle first" click="<?= "/" . _LANGUAGE_PACK . "/modules/" . $_GET['module'] . "/" . trim($_GET['file'], "/") . $form ?>"></div>
	<div class="button fa fa-question-circle" title="Dit zijn uw webwinkel categorieën. Ze zijn opgebouwd in dezelfde boom die zichtbaar is op uw webshop. De producten onder de categorie hebben een voorraadwijze. Deze wijze bepaald wat uw bezoekers wel of niet zien van uw voorraden."></div>
</div>

<?php
if($mb->num_rows($data))
{
	foreach($data AS $value)
	{
		?>
		<table class="view hoverable">
			<thead>
				<tr>
					<td><?= $mb->_translateReturn("table-headers", "name") ?></td>
					<td><?= $mb->_translateReturn("table-headers", "stock-type") ?></td>
					<td class="hide-mobile"><?= $mb->_translateReturn("table-headers", "count-products") ?></td>
					<td><?= $mb->_translateReturn("table-headers", "visible") ?></td>
					<td class="hide-mobile"><?= $mb->_translateReturn("table-headers", "updated") ?></td>
				</tr>
			</thead>
			
			<tbody>
				<tr click="<?= "/" . _LANGUAGE_PACK . "/modules/" . $_GET['module'] . "/" . trim($_GET['file'], "/") . $form . $value['categoryID'] ?>">
					<td><?= $value['name'] ?></td>
					<td><?= $mb->_runFunction("stock", "renameStockType", array($value['stock_type'])) ?></td>
					<td class="hide-mobile"><?= $value['products'] ?> <?= $mb->_translateReturn("table-headers", "count-products-inline") ?></td>
					<td><?= ($value['active'] ? "Ja" : "Nee") ?></td>
					<td class="hide-mobile"><?= $value['date_update'] ?></td>
				</tr>
				
				<?php
				$data_child_1 = $mb->_runFunction("categories", "view", array($_SESSION['merchantID'], "", "categories.name", "0,50", $value['categoryID']));
				
				if($mb->num_rows($data_child_1))
				{
					foreach($data_child_1 AS $value_child_1)
					{
						?>
						<tr click="<?= "/" . _LANGUAGE_PACK . "/modules/" . $_GET['module'] . "/" . trim($_GET['file'], "/") . $form . $value_child_1['categoryID'] ?>">
							<td>
								<span class="fa fa-ellipsis-h"></span>&nbsp;
								<?= $value_child_1['name'] ?>
							</td>
							<td><?= $mb->_runFunction("stock", "renameStockType", array($value_child_1['stock_type'])) ?></td>
							<td class="hide-mobile"><?= $value_child_1['products'] ?> <?= $mb->_translateReturn("table-headers", "count-products-inline") ?></td>
							<td><?= ($value_child_1['active'] ? "Ja" : "Nee") ?></td>
							<td class="hide-mobile"><?= $value_child_1['date_update'] ?></td>
						</tr>
						
						<?php
						$data_child_2 = $mb->_runFunction("categories", "view", array($_SESSION['merchantID'], "", "categories.name", "0,50", $value_child_1['categoryID']));
						
						if($mb->num_rows($data_child_2))
						{
							foreach($data_child_2 AS $value_child_2)
							{
								?>
								<tr click="<?= "/" . _LANGUAGE_PACK . "/modules/" . $_GET['module'] . "/" . trim($_GET['file'], "/") . $form . $value_child_2['categoryID'] ?>">
									<td>
										<span class="fa fa-ellipsis-h" style="margin: 0px 0px 0px 20px;"></span>&nbsp;
										<?= $value_child_2['name'] ?>
									</td>
									<td><?= $mb->_runFunction("stock", "renameStockType", array($value_child_2['stock_type'])) ?></td>
									<td class="hide-mobile"><?= $value_child_2['products'] ?> <?= $mb->_translateReturn("table-headers", "count-products-inline") ?></td>
									<td><?= ($value_child_2['active'] ? "Ja" : "Nee") ?></td>
									<td class="hide-mobile"><?= $value_child_2['date_update'] ?></td>
								</tr>
								<?php
							}
						}
					}
				}
				?>
			</tbody>
		</table>
		<?php
	}
}
?>

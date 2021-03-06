<?php
$mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "SET_BP", 1));

$data = $mb->_runFunction("products", "view", array($_SESSION['merchantID'], (isset($_GET['search_string']) ? trim($_GET['search_string'], "/") : ""), "products.name", "0,50"));
$form = "/form-mutatie/";
?>

<ul class="breadcrumbs">
	<li>Merchant</li>
	<li><?= $mb->_translateReturn("menu", "stock") ?></li>
	<li><?= $mb->_translateReturn("menu", "stock-mutations") ?></li>
</ul>

<div class="view-options">
	<input type="text" name="search" id="search" value="<?= (isset($_GET['search_string']) ? trim($_GET['search_string'], "/") : "") ?>" class="width-200" icon="fa-search" />
	<div class="button fa fa-plus-circle first" click="<?= "/" . _LANGUAGE_PACK . "/modules/" . $_GET['module'] . "/" . trim($_GET['file'], "/") . $form ?>"></div>
	<div class="button fa fa-question-circle" title="Zoek op een artikel. Als u doorklikt kunt u, per vestiging, een voorraad mutatie doorvoeren. Dit is een snellere weg dan via de product details, hier verschijnt alleen de optie tot voorraadmutatie en verder niets."></div>
</div>

<table class="view <?= count($data) ? "hoverable" : "" ?>">
	<thead>
		<tr>
			<td><?= $mb->_translateReturn("table-headers", "ac") ?></td>
			<td class="hide-mobile"><?= $mb->_translateReturn("table-headers", "sc") ?></td>
			<td><?= $mb->_translateReturn("table-headers", "product") ?></td>
			<td><?= $mb->_translateReturn("table-headers", "stock") ?></td>
			<td class="hide-mobile"><?= $mb->_translateReturn("table-headers", "visible") ?></td>
			<td class="hide-mobile"><?= $mb->_translateReturn("table-headers", "updated") ?></td>
		</tr>
	</thead>
	
	<tbody>
		<?php
		if(count($data))
		{
			foreach($data AS $value)
			{
				?>
				<tr click="<?= "/" . _LANGUAGE_PACK . "/modules/" . $_GET['module'] . "/" . trim($_GET['file'], "/") . $form . $value['productID'] ?>">
					<td><?= $value['article_code'] ?></td>
					<td class="hide-mobile"><?= $value['supplier_code'] ?></td>
					<td><?= $value['name'] ?></td>
					<td>
						<?= $value['stock'] . " " . $mb->_translateReturn("table-headers", "count-stock-inline") ?>
					</td>
					<td class="hide-mobile"><?= $mb->_runFunction("products", "translateVisibility", array($value['visibility'])) ?></td>
					<td class="hide-mobile"><?= $value['date_added'] ?></td>
				</tr>
				<?php
			}
		}
		else if(!isset($_GET['search_string']))
		{
			?>
			<tr>
				<td colspan="6" align="center"><?= $mb->_translateReturn("table-headers", "search-required") ?></td>
			</tr>
			<?php
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
<?php
$mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "SET_BP", 1));

$data = $mb->_runFunction("products", "view", array($_SESSION['merchantID'], (isset($_GET['search_string']) ? trim($_GET['search_string'], "/") : ""), "products.name", "0,50"));
$form = "/form-artikel/";
?>

<ul class="breadcrumbs">
	<li>Merchant</li>
	<li><?= $mb->_translateReturn("menu", "catalog") ?></li>
	<li><?= $mb->_translateReturn("menu", "article-management") ?></li>
</ul>

<div class="view-options">
	<input type="text" name="search" id="search" value="<?= (isset($_GET['search_string']) ? trim($_GET['search_string'], "/") : "") ?>" class="width-200" icon="fa-search" />
	<div class="button fa fa-plus-circle first" click="<?= "/" . _LANGUAGE_PACK . "/modules/" . $_GET['module'] . "/" . trim($_GET['file'], "/") . $form ?>"></div>
	<div class="button fa fa-question-circle" title="Dit is het overzicht van artikelen. In eerste instantie ziet u hier niets omdat u dient te zoeken op een artikel. Het vooraf tonen van artikelen zou teveel rekenkracht kosten."></div>
</div>

<table class="view <?= count($data) ? "hoverable" : "" ?>">
	<thead>
		<tr>
			<td><?= $mb->_translateReturn("table-headers", "ac") ?></td>
			<td class="hide-mobile"><?= $mb->_translateReturn("table-headers", "sc") ?></td>
			<td><?= $mb->_translateReturn("table-headers", "product") ?></td>
			<td class="hide-mobile"><?= $mb->_translateReturn("table-headers", "visible") ?></td>
			<td><?= $mb->_translateReturn("table-headers", "price") ?></td>
			<td class="hide-mobile"><?= $mb->_translateReturn("table-headers", "added") ?></td>
		</tr>
	</thead>
	
	<tbody>
		<?php
		if(count($data))
		{
			foreach($data AS $key => $value)
			{
				?>
				<tr click="<?= "/" . _LANGUAGE_PACK . "/modules/" . $_GET['module'] . "/" . trim($_GET['file'], "/") . $form . $value['productID'] ?>">
					<td><?= $value['article_code'] ?></td>
					<td class="hide-mobile"><?= $value['supplier_code'] ?></td>
					<td>
						<?= $value['status'] == 2 ? '<span class="fa fa-trash"></span>&nbsp;' : '' ?>
						<?= $value['name'] ?>
					</td>
					<td class="hide-mobile"><?= $mb->_runFunction("products", "translateVisibility", array($value['visibility'])) ?></td>
					<td>
						<?= $value['promo'] == true ? '<span class="fa fa-star"></span>&nbsp;' : '' ?>
						&euro;&nbsp;<?= number_format($value['price'], 2, ",", ".") ?>
					</td>
					<td class="hide-mobile"><?= $value['date_added'] ?></td>
				</tr>
				<?php
			}
		}
		else if(!isset($_GET['search']))
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
<?php
$mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "SET_BP", 1));

$data = $mb->_runFunction("promotions", "view", array($_SESSION['merchantID'], (isset($_GET['search_string']) ? trim($_GET['search_string'], "/") : ""), "promotions.name", "0,50", 1));
$form = "/form-catalogus/";
?>

<ul class="breadcrumbs">
	<li>Merchant</li>
	<li><?= $mb->_translateReturn("menu", "promotions") ?></li>
	<li><?= $mb->_translateReturn("menu", "catalog-discounts") ?></li>
</ul>

<div class="view-options">
	<input type="text" name="search" id="search" value="<?= (isset($_GET['search_string']) ? trim($_GET['search_string'], "/") : "") ?>" class="width-200" icon="fa-search" />
	<div class="button fa fa-plus-circle first" click="<?= "/" . _LANGUAGE_PACK . "/modules/" . $_GET['module'] . "/" . trim($_GET['file'], "/") . $form ?>"></div>
	<div class="button fa fa-question-circle" title="Wilt u een aanbieding instellen op een bepaald artikel voor een bepaalde periode? Gebruik dan een catalogus aanbieding. Zolang de periode actief is kunt u een percentage of een vast bedrag aan korting instellen. Zodra de periode is afgelopen gaat de prijs automatisch terug naar de standaard."></div>
</div>

<table class="view <?= $mb->num_rows($data) ? "hoverable" : "" ?>">
	<thead>
		<tr>
			<td><?= $mb->_translateReturn("table-headers", "name") ?></td>
			<td><?= $mb->_translateReturn("table-headers", "date-from") ?></td>
			<td><?= $mb->_translateReturn("table-headers", "date-to") ?></td>
			<td class="hide-mobile"><?= $mb->_translateReturn("table-headers", "products") ?></td>
			<td class="hide-mobile"><?= $mb->_translateReturn("table-headers", "updated") ?></td>
		</tr>
	</thead>
	
	<tbody>
		<?php
		if($mb->num_rows($data))
		{
			foreach($data AS $value)
			{
				?>
				<tr click="<?= "/" . _LANGUAGE_PACK . "/modules/" . $_GET['module'] . "/" . trim($_GET['file'], "/") . $form . $value['promotionID'] ?>">
					<td><?= $value['name'] ?></td>
					<td><?= $value['date_from'] ?></td>
					<td><?= $value['date_to'] ?></td>
					<td class="hide-mobile"><?= $value['products'] ?> <?= $mb->_translateReturn("table-headers", "products-inline") ?></td>
					<td class="hide-mobile"><?= $value['date_added'] ?></td>
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
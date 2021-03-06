<?php
$mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "TPB_BB", 1));

$data = $mb->_runFunction("orders", "view", array($_SESSION['merchantID'], (isset($_GET['search_string']) ? trim($_GET['search_string'], "/") : ""), "orders.date_added DESC", "0,50", 4));
$form = "/form-order/";
?>

<ul class="breadcrumbs">
	<li>Merchant</li>
	<li><?= $mb->_translateReturn("menu", "sales") ?></li>
	<li><?= $mb->_translateReturn("menu", "closed-orders") ?></li>
</ul>

<div class="view-options">
	<input type="text" name="search" id="search" value="<?= (isset($_GET['search_string']) ? trim($_GET['search_string'], "/") : "") ?>" class="width-200" icon="fa-search" />
	<div class="button fa fa-question-circle" title="In dit overzicht staan bestellingen die afgerond zijn (producten zijn afgeboekt van de voorraad) maar nog niet betaald. Ook staan er bestellingen in waar een teveel betaling openstaat (crediteuren)."></div>
</div>

<table class="view <?= $mb->num_rows($data) ? "hoverable" : "" ?>">
	<thead>
		<tr>
			<td><?= $mb->_translateReturn("table-headers", "orderid") ?></td>
			<td class="hide-mobile"><?= $mb->_translateReturn("table-headers", "date") ?></td>
			<td><?= $mb->_translateReturn("table-headers", "customer") ?></td>
			<td><?= $mb->_translateReturn("table-headers", "grand_total") ?></td>
			<td><?= $mb->_translateReturn("table-headers", "payed") ?></td>
		</tr>
	</thead>
	
	<tbody>
		<?php
		if($mb->num_rows($data))
		{
			foreach($data AS $value)
			{
				?>
				<tr click="<?= "/" . _LANGUAGE_PACK . "/modules/" . $_GET['module'] . "/" . trim($_GET['file'], "/") . $form . $value['orderID'] ?>">
					<td><?= $value['order_reference'] ?></td>
					<td><?= $value['date_added'] ?></td>
					<td><?= $value['customer_name'] ?></td>
					<td>&euro;&nbsp;<?= _frontend_float($value['grand_total']) ?></td>
					<td class="<?= $value['payed'] > $value['grand_total'] ? "yellow" : "red" ?>">&euro;&nbsp;<?= _frontend_float($value['payed']) ?></td>
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
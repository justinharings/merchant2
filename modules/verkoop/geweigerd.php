<?php
$mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "TPB_BB", 1));

$data = $mb->_runFunction("orders", "view", array($_SESSION['merchantID'], (isset($_GET['search_string']) ? trim($_GET['search_string'], "/") : ""), "orders.date_added DESC", "0,50", 3));
$form = "/form-order/";
?>

<ul class="breadcrumbs">
	<li>Merchant</li>
	<li><?= $mb->_translateReturn("menu", "sales") ?></li>
	<li><?= $mb->_translateReturn("menu", "canceled-orders") ?></li>
</ul>

<div class="view-options">
	<input type="text" name="search" id="search" value="<?= (isset($_GET['search_string']) ? trim($_GET['search_string'], "/") : "") ?>" class="width-200" icon="fa-search" />
</div>

<table class="view <?= $mb->num_rows($data) ? "hoverable" : "" ?>">
	<thead>
		<tr>
			<td><?= $mb->_translateReturn("table-headers", "orderid") ?></td>
			<td><?= $mb->_translateReturn("table-headers", "date") ?></td>
			<td><?= $mb->_translateReturn("table-headers", "customer") ?></td>
			<td><?= $mb->_translateReturn("table-headers", "grand_total") ?></td>
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
				<tr click="<?= "/" . _LANGUAGE_PACK . "/modules/" . $_GET['module'] . "/" . trim($_GET['file'], "/") . $form . $value['orderID'] ?>">
					<td><?= $value['order_reference'] ?></td>
					<td><?= $value['date_added'] ?></td>
					<td><?= $value['customer_name'] ?></td>
					<td>&euro;&nbsp;<?= _frontend_float($value['grand_total']) ?></td>
					<td class="hide-mobile"><?= $value['status'] ?></td>
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
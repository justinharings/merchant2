<?php
$mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "TPB_BB", 1));

$data = $mb->_runFunction("paylink", "view", array($_SESSION['merchantID'], (isset($_GET['search_string']) ? trim($_GET['search_string'], "/") : ""), "paylink.date_added DESC", "0,50"));
$form = "/form-betaallink/";

$merchant = $mb->_runFunction("merchant", "load", array($_SESSION['merchantID']));
?>

<ul class="breadcrumbs">
	<li>Merchant</li>
	<li><?= $mb->_translateReturn("menu", "sales") ?></li>
	<li><?= $mb->_translateReturn("menu", "paylink") ?></li>
</ul>

<div class="view-options">
	<input type="text" name="search" id="search" value="<?= (isset($_GET['search_string']) ? trim($_GET['search_string'], "/") : "") ?>" class="width-200" icon="fa-search" />
	<div class="button fa fa-plus-circle" click="<?= "/" . _LANGUAGE_PACK . "/modules/" . $_GET['module'] . "/" . trim($_GET['file'], "/") . $form ?>"></div>
</div>

<table class="view">
	<thead>
		<tr>
			<td><?= $mb->_translateReturn("table-headers", "orderid") ?></td>
			<td><?= $mb->_translateReturn("table-headers", "description") ?></td>
			<td class="hide-mobile"><?= $mb->_translateReturn("table-headers", "module") ?></td>
			<td><?= $mb->_translateReturn("table-headers", "amount") ?></td>
			<td width="1"><?= $mb->_translateReturn("table-headers", "status") ?></td>
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
					<td><?= $value['order_reference'] ?></td>
					<td><?= $value['description'] ?></td>
					<td class="hide-mobile"><?= $value['payment_module'] ?></td>
					<td>&euro;&nbsp;<?= _frontend_float($value['amount']) ?></td>
					<td><?= $merchant['website_url'] . "paylink/" . $value['key'] ?></td>
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
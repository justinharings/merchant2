<?php
$mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "SET_BM", 1));

$data = $mb->_runFunction("payment_methods", "view", array($_SESSION['merchantID'], (isset($_GET['search_string']) ? trim($_GET['search_string'], "/") : ""), "payment_methods.name", "0,50"));

$form = "/form-betalingsmethoden/";
?>

<ul class="breadcrumbs">
	<li>Merchant</li>
	<li><?= $mb->_translateReturn("menu", "settings") ?></li>
	<li><?= $mb->_translateReturn("menu", "payment-methods") ?></li>
</ul>

<div class="view-options">
	<input type="text" name="search" id="search" value="<?= (isset($_GET['search_string']) ? trim($_GET['search_string'], "/") : "") ?>" class="width-200" icon="fa-search" />
	<div class="button fa fa-plus-circle first" click="<?= "/" . _LANGUAGE_PACK . "/modules/" . $_GET['module'] . "/" . trim($_GET['file'], "/") . $form ?>"></div>
	<div class="button fa fa-question-circle" title="Dit zijn uw betalingsmethoden. U kunt zowel methoden instellen voor in uw POS als voor op uw webshop. Voor de webshop hebben we verschillende klant en klare koppelingsmogelijkheden met betalingsproviders."></div>
</div>

<table class="view <?= $mb->num_rows($data) ? "hoverable" : "" ?>">
	<thead>
		<tr>
			<td><?= $mb->_translateReturn("table-headers", "name") ?></td>
			<td><?= $mb->_translateReturn("table-headers", "module") ?></td>
			<td class="hide-mobile"><?= $mb->_translateReturn("table-headers", "pos") ?></td>
			<td><?= $mb->_translateReturn("table-headers", "updated") ?></td>
			<td class="hide-mobile"><?= $mb->_translateReturn("table-headers", "added") ?></td>
		</tr>
	</thead>
	
	<tbody>
		<?php
		if($mb->num_rows($data))
		{
			$_methods = $mb->_runFunction("payment_methods", "payment_modules", array());
			
			foreach($data AS $value)
			{
				if($value['module'] != "")
				{
					$key = _search_for_id($value['module'], $_methods);
					$value['module'] = $_methods[$key]['name'];
				}
				
				?>
				<tr click="<?= "/" . _LANGUAGE_PACK . "/modules/" . $_GET['module'] . "/" . trim($_GET['file'], "/") . $form . $value['paymentID'] ?>">
					<td><?= $value['name'] ?></td>
					<td><?= ($value['module'] ? $value['module'] : "n.v.t.") ?></td>
					<td class="hide-mobile"><?= ($value['pos'] ? "Ja" : "Nee") ?></td>
					<td><?= $value['date_update'] ?></td>
					<td class="hide-mobile"><?= $value['date_added'] ?></td>
				</tr>
				<?php
			}
		}
		else
		{
			?>
			<tr>
				<td colspan="4" align="center"><?= $mb->_translateReturn("table-headers", "no-results") ?></td>
			</tr>
			<?php
		}
		?>
	</tbody>
</table>
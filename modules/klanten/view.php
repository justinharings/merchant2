<?php
$mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "SET_BP", 1));

$data = $mb->_runFunction("customers", "view", array($_SESSION['merchantID'], (isset($_GET['search_string']) ? trim($_GET['search_string'], "/") : ""), "customers.name", "0,50"));
$form = "/form-klant/";
?>

<ul class="breadcrumbs">
	<li>Merchant</li>
	<li><?= $mb->_translateReturn("menu", "customers") ?></li>
</ul>

<div class="view-options">
	<input type="text" name="search" id="search" value="<?= (isset($_GET['search_string']) ? trim($_GET['search_string'], "/") : "") ?>" class="width-200" icon="fa-search" />
	<div class="button fa fa-plus-circle" click="<?= "/" . _LANGUAGE_PACK . "/modules/" . $_GET['module'] . "/" . trim($_GET['file'], "/") . $form ?>"></div>
</div>

<table class="view <?= $mb->num_rows($data) ? "hoverable" : "" ?>">
	<thead>
		<tr>
			<td><?= $mb->_translateReturn("table-headers", "name") ?></td>
			<td class="hide-mobile"><?= $mb->_translateReturn("table-headers", "company") ?></td>
			<td class="hide-mobile"><?= $mb->_translateReturn("table-headers", "city") ?></td>
			<td><?= $mb->_translateReturn("table-headers", "phone") ?></td>
			<td><?= $mb->_translateReturn("table-headers", "email_address") ?></td>
		</tr>
	</thead>
	
	<tbody>
		<?php
		if($mb->num_rows($data))
		{
			foreach($data AS $value)
			{
				?>
				<tr click="<?= "/" . _LANGUAGE_PACK . "/modules/" . $_GET['module'] . "/" . trim($_GET['file'], "/") . $form . $value['customerID'] ?>">
					<td><?= $value['name'] ?></td>
					<td class="hide-mobile"><?= $value['company'] ?></td>
					<td class="hide-mobile"><?= $value['city'] . ", " . $value['country'] ?></td>
					<td><?= $value['phone'] == "" ? "Onbekend" : $value['phone'] ?></td>
					<td><?= $value['email_address'] == "" ? "Onbekend" : $value['email_address'] ?></td>
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
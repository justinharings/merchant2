<?php
$mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "SET_BB", 1));

$data = $mb->_runFunction("order_statuses", "view", array($_SESSION['merchantID'], (isset($_GET['search_string']) ? trim($_GET['search_string'], "/") : ""), "order_statuses.name", "0,50"));
$form = "/form-bestelstatus/";
?>

<ul class="breadcrumbs">
	<li>Merchant</li>
	<li><?= $mb->_translateReturn("menu", "settings") ?></li>
	<li><?= $mb->_translateReturn("menu", "order-statuses") ?></li>
</ul>

<div class="view-options">
	<input type="text" name="search" id="search" value="<?= (isset($_GET['search_string']) ? trim($_GET['search_string'], "/") : "") ?>" class="width-200" icon="fa-search" />
	<div class="button fa fa-plus-circle first" click="<?= "/" . _LANGUAGE_PACK . "/modules/" . $_GET['module'] . "/" . trim($_GET['file'], "/") . $form ?>"></div>
	<div class="button fa fa-question-circle" title="Dit zijn uw bestelstatussen. U kunt verschillende vormen aanmaken om zo uw bedrijfsproces te optimaliseren. Let wel goed op de instellingen, deze kunnen de voorraad beïnvloeden."></div>
</div>

<table class="view <?= $mb->num_rows($data) ? "hoverable" : "" ?>">
	<thead>
		<tr>
			<td><?= $mb->_translateReturn("table-headers", "name") ?></td>
			<td class="hide-mobile"><?= $mb->_translateReturn("table-headers", "default") ?></td>
			<td><?= $mb->_translateReturn("table-headers", "updated") ?></td>
			<td class="hide-mobile"><?= $mb->_translateReturn("table-headers", "added") ?></td>
		</tr>
	</thead>
	
	<tbody>
		<?php
		if($mb->num_rows($data))
		{
			foreach($data AS $value)
			{
				?>
				<tr click="<?= "/" . _LANGUAGE_PACK . "/modules/" . $_GET['module'] . "/" . trim($_GET['file'], "/") . $form . $value['statusID'] ?>">
					<td><?= $value['name'] ?></td>
					<td class="hide-mobile"><?= ($value['default'] ? "Ja" : "Nee") ?></td>
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
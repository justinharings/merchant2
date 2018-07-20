<?php
$mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "SET_VB", 1));

$data = $mb->_runFunction("shipment_methods", "view", array($_SESSION['merchantID'], (isset($_GET['search_string']) ? trim($_GET['search_string'], "/") : ""), "shipment_methods.name", "0,50"));

$form = "/form-verzendmethode/";
?>

<ul class="breadcrumbs">
	<li>Merchant</li>
	<li><?= $mb->_translateReturn("menu", "settings") ?></li>
	<li><?= $mb->_translateReturn("menu", "shipping-methods") ?></li>
</ul>

<div class="view-options">
	<input type="text" name="search" id="search" value="<?= (isset($_GET['search_string']) ? trim($_GET['search_string'], "/") : "") ?>" class="width-200" icon="fa-search" />
	<div class="button fa fa-plus-circle first" click="<?= "/" . _LANGUAGE_PACK . "/modules/" . $_GET['module'] . "/" . trim($_GET['file'], "/") . $form ?>"></div>
	<div class="button fa fa-question-circle" title="Dit zijn al uw verzend- en afhaalmethodes. Verzendopties kunt u uitgebreid instellen zodat ze aansluiten bij uw werkflow. Sommige methodes kunt u ook samenvoegen zodat de klant één keer betaald wanneer pakketten samengevoegd kunnen worden."></div>
</div>

<table class="view <?= $mb->num_rows($data) ? "hoverable" : "" ?>">
	<thead>
		<tr>
			<td><?= $mb->_translateReturn("table-headers", "name") ?></td>
			<td class="hide-mobile"><?= $mb->_translateReturn("table-headers", "courier") ?></td>
			<td><?= $mb->_translateReturn("table-headers", "updated") ?></td>
			<td><?= $mb->_translateReturn("table-headers", "added") ?></td>
		</tr>
	</thead>
	
	<tbody>
		<?php
		
		if($mb->num_rows($data))
		{
			foreach($data AS $value)
			{
				?>
				<tr click="<?= "/" . _LANGUAGE_PACK . "/modules/" . $_GET['module'] . "/" . trim($_GET['file'], "/") . $form . $value['shipmentID'] ?>">
					<td><?= $value['name'] ?></td>
					<td class="hide-mobile"><?= $value['courier'] ?></td>
					<td><?= $value['date_update'] ?></td>
					<td><?= $value['date_added'] ?></td>
				</tr>
				<?php
			}
		}
		else
		{
			?>
			<tr>
				<td colspan="3" align="center"><?= $mb->_translateReturn("table-headers", "no-results") ?></td>
			</tr>
			<?php
		}
		?>
	</tbody>
</table>
<?php
$mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "TPB_SS", 1));

$data = $mb->_runFunction("cms", "viewSms", array($_SESSION['merchantID'], (isset($_GET['search_string']) ? trim($_GET['search_string'], "/") : ""), "template_sms.name", "0,50"));
$form = "/form-sms/";
?>

<ul class="breadcrumbs">
	<li>Merchant</li>
	<li><?= $mb->_translateReturn("menu", "cms") ?></li>
	<li><?= $mb->_translateReturn("menu", "sms-templates") ?></li>
</ul>

<div class="view-options">
	<input type="text" name="search" id="search" value="<?= (isset($_GET['search_string']) ? trim($_GET['search_string'], "/") : "") ?>" class="width-200" icon="fa-search" />
	<div class="button fa fa-plus-circle first" click="<?= "/" . _LANGUAGE_PACK . "/modules/" . $_GET['module'] . "/" . trim($_GET['file'], "/") . $form ?>"></div>
	<div class="button fa fa-question-circle" title="Merchant is een centraal punt van communicatie met uw klanten. Via SMS sjablonen kunt u berichten vooraf opmaken die gebruikt gaan worden voor communicatie. Bijvoorbeeld bij een nieuwe order, een betaling of overige zaken. Wees met SMS berichten wel voorzichtig.. Veel klanten waarderen het niet om gespamd te worden."></div>
</div>

<table class="view <?= $mb->num_rows($data) ? "hoverable" : "" ?>">
	<thead>
		<tr>
			<td><?= $mb->_translateReturn("table-headers", "name") ?></td>
			<td><?= $mb->_translateReturn("table-headers", "type") ?></td>
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
				<tr click="<?= "/" . _LANGUAGE_PACK . "/modules/" . $_GET['module'] . "/" . trim($_GET['file'], "/") . $form . $value['smsID'] ?>">
					<td><?= $value['name'] ?></td>
					<td><?= $value['type'] ?></td>
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
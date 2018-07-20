<?php
$mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "TPB_PT", 1));

$data = $mb->_runFunction("cms", "viewContent", array($_SESSION['merchantID'], (isset($_GET['search_string']) ? trim($_GET['search_string'], "/") : ""), "content.name", "0,50"));
$form = "/form-content/";
?>

<ul class="breadcrumbs">
	<li>Merchant</li>
	<li><?= $mb->_translateReturn("menu", "cms") ?></li>
	<li><?= $mb->_translateReturn("menu", "page-content") ?></li>
</ul>

<div class="view-options">
	<input type="text" name="search" id="search" value="<?= (isset($_GET['search_string']) ? trim($_GET['search_string'], "/") : "") ?>" class="width-200" icon="fa-search" />
	<div class="button fa fa-plus-circle first" click="<?= "/" . _LANGUAGE_PACK . "/modules/" . $_GET['module'] . "/" . trim($_GET['file'], "/") . $form ?>"></div>
	<div class="button fa fa-question-circle" title="Dit is het overzicht met uw pagina teksten. U kunt gemakkelijk de teksten van uw pagina's aanpassen via Merchant. Door voldoende informatie in te vullen gaat Google beter om met uw website."></div>
</div>

<table class="view <?= $mb->num_rows($data) ? "hoverable" : "" ?>">
	<thead>
		<tr>
			<td><?= $mb->_translateReturn("table-headers", "name") ?></td>
			<td><?= $mb->_translateReturn("table-headers", "seo_url") ?></td>
			<td><?= $mb->_translateReturn("table-headers", "added") ?></td>
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
				<tr click="<?= "/" . _LANGUAGE_PACK . "/modules/" . $_GET['module'] . "/" . trim($_GET['file'], "/") . $form . $value['contentID'] ?>">
					<td><?= $value['name'] ?></td>
					<td><?= $value['seo_url'] ?></td>
					<td><?= $value['date_added'] ?></td>
					<td class="hide-mobile"><?= $value['date_update'] ?></td>
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
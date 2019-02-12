<?php
$mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "SET_BP", 1));

$data = $mb->_runFunction("customers", "viewCards", array($_SESSION['merchantID'], (isset($_GET['search_string']) ? trim($_GET['search_string'], "/") : ""), "cards.name", "0,50"));
$form = "/form-card/";
?>

<ul class="breadcrumbs">
	<li>Merchant</li>
	<li>Klantenkaart opties</li>
</ul>

<div class="view-options">
	<input type="text" name="search" id="search" value="<?= (isset($_GET['search_string']) ? trim($_GET['search_string'], "/") : "") ?>" class="width-200" icon="fa-search" />
	<div class="button fa fa-plus-circle first" click="<?= "/" . _LANGUAGE_PACK . "/modules/" . $_GET['module'] . "/" . trim($_GET['file'], "/") . $form ?>"></div>
</div>

<table class="view <?= $mb->num_rows($data) ? "hoverable" : "" ?>">
	<thead>
		<tr>
			<td>Naam</td>
			<td>Prijs</td>
		</tr>
	</thead>
	
	<tbody>
		<?php
		if($mb->num_rows($data))
		{
			foreach($data AS $value)
			{
				?>
				<tr click="<?= "/" . _LANGUAGE_PACK . "/modules/" . $_GET['module'] . "/" . trim($_GET['file'], "/") . $form . $value['cardID'] ?>">
					<td><?= $value['name'] ?></td>
					<td><?= number_format($value['price'], 2, ",", ".") ?></td>
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
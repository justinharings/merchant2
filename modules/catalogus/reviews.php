<?php
$mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "SET_BP", 1));

$data = $mb->_runFunction("reviews", "view", array($_SESSION['merchantID'], "reviews.approved", "0,50", "IN(0,1)"));
$form = "/form-review/";
?>

<ul class="breadcrumbs">
	<li>Merchant</li>
	<li><?= $mb->_translateReturn("menu", "catalog") ?></li>
	<li><?= $mb->_translateReturn("menu", "reviews-management") ?></li>
</ul>

<div class="view-options">
	<div class="button fa fa-question-circle" title="Dit zijn de recensies van uw klanten. U kunt zelf bepalen welke recensies er zichtbaar zijn en welke niet. Recensies verschijnen onder de product details, in de overzichten en worden ook meegegeven naar Google Shopping."></div>
</div>

<table class="view <?= $mb->num_rows($data) ? "hoverable" : "" ?>">
	<thead>
		<tr>
			<td><?= $mb->_translateReturn("table-headers", "writer") ?></td>
			<td><?= $mb->_translateReturn("table-headers", "stars") ?></td>
			<td><?= $mb->_translateReturn("table-headers", "product") ?></td>
			<td><?= $mb->_translateReturn("table-headers", "visible") ?></td>
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
				<tr click="<?= "/" . _LANGUAGE_PACK . "/modules/" . $_GET['module'] . "/" . trim($_GET['file'], "/") . $form . $value['reviewID'] ?>">
					<td><?= $value['name'] ?></td>
					<td>
						<?php
						for($i = 1; $i < 6; $i++)
						{
							if($value['stars'] >= $i)
							{
								print '<span class="fa fa-star"></span>';
							}
							else
							{
								print '<span class="fa fa-star-o"></span>';
							}
						}
						?>
					</td>
					<td><?= _chopString($value['product'], 28) ?></td>
					<td><span class="fa large <?= $value['approved'] ? "fa-check-circle green" : "fa-times-circle red" ?>"></span></td>
					<td class="hide-mobile"><?= $value['date_added'] ?></td>
				</tr>
				<?php
			}
		}
		else
		{
			?>
			<tr>
				<td colspan="5" align="center"><?= $mb->_translateReturn("table-headers", "no-results") ?></td>
			</tr>
			<?php
		}
		?>
	</tbody>
</table>
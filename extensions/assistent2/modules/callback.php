<?php
$query = sprintf(
	"	SELECT		ass2_callback.*
		FROM		ass2_callback
		WHERE		ass2_callback.callbackID = %d",
	intval($_GET['callbackID'])
);
$result = $mb->query($query);
$row = $mb->fetch_assoc($result);

$product = $mb->_runFunction("products", "load", array(intval($row['productID'])));
?>

<div class="container linen">
	<div class="inner-container">
		<div class="title fa fa-phone"></div>
		<div class="menu-button fa fa-bars"></div>
		
		<div class="menu">
			<ul>
				<li browse="/extensions/assistent2/library/php/callback.php?callbackID=<?= intval($_GET['callbackID']) ?>">
					<span class="fa fa-check"></span>
					Afgerond en doorgaan
				</li>
			</ul>
		</div>
		
		<div class="content">
			<h1>Terugbelverzoek behandelen</h1>
			
			<div class="center">
				<br/><br/><br/>
				
				<h2 style="font-size: 60px;">
					Terugbelverzoek:<br/>
					<?= $row['phone_number'] ?>
				</h2>
				
				<br/><br/>
				
				Deze klant wil graag meer informatie over het volgende product:<br/>
				<h2><?= $product['name'] ?></h2><br/>
				
				<small>
					Dit product kost <?= $product['price'] ?> euro en wordt
					<u><?= (count($product['pricecheck']) == 0 ? "niet" : "") ?></u> vergeleken met andere webshops.
				</small>
			</div>
		</div>
		
		<div class="footer">
			<div class="date-time-stamp">
				<?= date("d-m-Y H:i") ?> uur
			</div>
			
			<div class="button refresh fa fa-sync"></div>
			<div class="button vuurwerk fa fa-fire"></div>
			
			<div class="spacer"></div>
			
			<div class="button calendar fa fa-calendar"></div>
			<div class="button core_products fa fa-bullseye"></div>
			<div class="button cleanup fa fa-trash"></div>
		</div>
	</div>
</div>
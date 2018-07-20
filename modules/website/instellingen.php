<?php
$mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "WEB", 1));
$data = $mb->_runFunction("website", "load", array($_SESSION['merchantID']));
?>

<ul class="breadcrumbs">
	<li>Merchant</li>
	<li><?= $mb->_translateReturn("menu", "website") ?></li>
</ul>

<form method="post" id="form" action="/library/php/posts/website/instellingen.php">
	<input type="hidden" name="returnURL" id="returnURL" value="<?= "/" . _LANGUAGE_PACK . "/modules/" . $_GET['module'] . "/" . $_GET['file'] ?>" />
	
	<div class="simple-form">
		<div class="form-header">
			<h1><?= $mb->_translateReturn("menu", "website") ?></h1>
			
			<input type="button" name="return" id="return" value="<?= $mb->_translateReturn("forms", "button-cancel") ?>" class="show-load" />
			<input type="submit" name="save" id="save" value="<?= $mb->_translateReturn("forms", "button-save") ?>" class="red show-load validate-form" />
		</div>
		
		<div class="form-content">
			<div class="content-header">
				<span class="fa fa-pencil-square-o"></span>
				<?= $mb->_translateReturn("forms", "legend-manage-website-note") ?>
			</div>
			
			<textarea name="note_content" id="note_content" class="width-400" holder="<?= $mb->_translateReturn("forms", "form-website-notification") ?>" holder-eg="<?= $mb->_translateReturn("forms", "form-website-notification-eg") ?>"><?= (isset($data['note_content']) ? $data['note_content'] : "") ?></textarea>
		</div>
		
		<div class="form-content">
			<div class="content-header">
				<span class="fa fa-pencil-square-o"></span>
				<?= $mb->_translateReturn("forms", "legend-options") ?>
			</div>
			
			<input type="checkbox" <?= isset($data['show_shipment']) && $data['show_shipment'] == 1 ? "checked=\"checked\"" : "" ?> name="show_shipment" id="show_shipment" value="1" holder="<?= $mb->_translateReturn("forms", "form-website-shipment") ?>" question="Gebruikt uw webshop niets met 'verzenden'? Wordt bijvoorbeeld alles afgehaald bij u in de winkel? Zet dan dit vinkje uit. De webshop toont dan nergens iets wat met verzendkosten te maken heeft." />
		</div>
		
		<div class="form-content">
			<div class="content-header">
				<span class="fa fa-pencil-square-o"></span>
				<?= $mb->_translateReturn("forms", "legend-others") ?>
			</div>
			
			<input type="text" name="minimum_order_amount" id="minimum_order_amount" value="<?= $data['minimum_order_amount'] ? $data['minimum_order_amount'] : 0 ?>" class="width-150" icon="fa-euro" holder="<?= $mb->_translateReturn("forms", "form-website-minimum") ?>" question="Hanteert u een minimaal order bedrag op uw webshop? Vul dit dan hier in. Uw klant kan dan niet doorgaan met de bestelling wanneer het minimale bedrag niet gehaald is. Heeft u dit niet? Vul dan 0 (nul) in." />
		</div>
	</div>
</form>
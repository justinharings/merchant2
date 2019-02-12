<?php
$mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "POS_KI", 1));
$data = $mb->_runFunction("customers", "loadCard", array($_GET['dataID']));
?>

<ul class="breadcrumbs">
	<li>Merchant</li>
	<li>Klantenkaart opties</li>
</ul>

<form method="post" id="form" action="/library/php/posts/klanten/card.php">
	<input type="hidden" name="returnURL" id="returnURL" value="<?= "/" . _LANGUAGE_PACK . "/modules/" . $_GET['module'] . "/" . $_GET['file'] ?>" />
	<input type="hidden" name="cardID" id="cardID" value="<?= isset($_GET['dataID']) ? intval($_GET['dataID']) : 0 ?>" />
	
	<div class="simple-form">
		<div class="form-header">
			<h1>Klantenkaart opties</h1>
			
			<input type="button" name="return" id="return" value="<?= $mb->_translateReturn("forms", "button-cancel") ?>" class="show-load" />
			<input type="submit" name="save" id="save" value="<?= $mb->_translateReturn("forms", "button-save") ?>" class="red show-load validate-form" />
		</div>
		
		<div class="form-content">
			<div class="content-header">
				<span class="fa fa-pencil-square-o"></span>
				Pakket gegevens
			</div>
			
			<input type="text" name="name" id="name" value="<?= $data['name'] ?>" class="width-300 margin" holder="Naam van het pakket" />
			<input type="text" name="price" id="price" value="<?= $data['price'] ?>" class="width-100" icon="fa-euro" holder="Verkoopprijs" />
		</div>
		
		<div class="form-content">
			<div class="content-header">
				<span class="fa fa-pencil-square-o"></span>
				Maandelijks betalingen
			</div>
			
			<input <?= $data['monthly'] == 1 ? "checked=\"checked\"" : "" ?> type="checkbox" name="monthly" id="monthly" value="1" />
		</div>
	</div>
</form>
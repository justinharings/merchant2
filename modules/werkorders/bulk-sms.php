<?php
$mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "WOR", 1));
$data = $mb->_runFunction("workorders", "loadSettings", array($_SESSION['merchantID']));
?>

<ul class="breadcrumbs">
	<li>Merchant</li>
	<li>Bulk SMS sturen</li>
</ul>

<form method="post" id="form" action="/library/php/posts/werkorders/bulk.php">
	<input type="hidden" name="returnURL" id="returnURL" value="<?= "/" . _LANGUAGE_PACK . "/modules/" . $_GET['module'] . "/" . $_GET['file'] ?>" />
	
	<div class="simple-form">
		<div class="form-header">
			<h1>Bulk SMS sturen</h1>
			
			<input type="button" name="return" id="return" value="<?= $mb->_translateReturn("forms", "button-cancel") ?>" class="show-load" />
			<input type="submit" name="save" id="save" value="Versturen" class="red show-load validate-form" />
		</div>
		
		<div class="form-content">
			<div class="content-header">
				<span class="fa fa-pencil-square-o"></span>
				<?= $mb->_translateReturn("forms", "legend-manage-content") ?>
			</div>
			
			<textarea name="content" id="content" class="width-400" max-characters="160" holder="Tekst SMS bericht" holder-eg="<?= $mb->_translateReturn("forms", "form-workorders-receipt-content-eg") ?>" question="Je kunt afgeronde werkorders een SMS versturen. Bijvoorbeeld handig wanneer je iets wil mededelen."></textarea>
		</div>
	</div>
</form>
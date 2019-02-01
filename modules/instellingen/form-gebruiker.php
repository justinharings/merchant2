<?php
$mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "SET_GB", 1));

if(isset($_GET['dataID']))
{
	$data = $mb->_runFunction("users", "load", array($_GET['dataID']));
}
?>

<ul class="breadcrumbs">
	<li>Merchant</li>
	<li><?= $mb->_translateReturn("menu", "settings") ?></li>
	<li><?= $mb->_translateReturn("menu", "manage-users") ?></li>
	<li><?= (isset($_GET['dataID']) ? $data['first_name'] : $mb->_translateReturn("forms", "add")) ?></li>
</ul>

<form method="post" id="form" action="/library/php/posts/instellingen/gebruikers.php" enctype="multipart/form-data">
	<input type="hidden" name="userID" id="userID" value="<?= isset($_GET['dataID']) ? $_GET['dataID'] : 0 ?>" />
	<input type="hidden" name="returnURL" id="returnURL" value="<?= "/" . _LANGUAGE_PACK . "/modules/" . $_GET['module'] . "/" . $_GET['file'] ?>" />
	
	<div class="simple-form">
		<div class="form-header">
			<h1><?= (isset($_GET['dataID']) ? $mb->_translateReturn("forms", "edit", array($data['first_name'])) : $mb->_translateReturn("forms", "add-new-title")) ?></h1>
			
			<input type="button" name="return" id="return" value="<?= $mb->_translateReturn("forms", "button-cancel") ?>" class="show-load" />
			
			<?php
			if(isset($_GET['dataID']) && intval($_GET['dataID']) != $_SESSION['userID'])
			{
				?>
				<input type="button" name="delete-item" id="delete-item" value="<?= $mb->_translateReturn("forms", "button-delete") ?>" class="white show-load" />
				<?php
			}
			?>
			
			<input type="submit" name="save" id="save" value="<?= $mb->_translateReturn("forms", "button-save") ?>" class="red show-load validate-form" />
		</div>
		
		
		<div class="form-content">
			<div class="content-header">
				<span class="fa fa-pencil-square-o"></span>
				<?= $mb->_translateReturn("forms", "legend-personal-information") ?>
			</div>
			
			<input type="text" name="first_name" id="first_name" value="<?= isset($_GET['dataID']) ? $data['first_name'] : "" ?>" class="width-300 margin" holder="<?= $mb->_translateReturn("forms", "form-users-name") ?>" holder-eg="<?= $mb->_translateReturn("forms", "form-users-name-eg") ?>" validation-required="true" validation-type="text" />
			<input type="text" name="email_address" id="email_address" value="<?= isset($_GET['dataID']) ? $data['email_address'] : "" ?>" class="width-300 double-margin" holder="<?= $mb->_translateReturn("forms", "form-users-email") ?>" holder-eg="<?= $mb->_translateReturn("forms", "form-users-email-eg") ?>" validation-required="true" validation-type="email" />
			
			<?php
			if($_SESSION['administrator'] == 1)
			{
				?>
				<input type="checkbox" <?= isset($_GET['dataID']) && intval($_GET['dataID']) == $_SESSION['userID'] ? "disabled=\"disabled\"" : "" ?> <?= isset($_GET['dataID']) && $data['admin'] == 1 ? "checked=\"checked\"" : "" ?> name="administrator" id="administrator" value="1" class="double-margin" holder="<?= $mb->_translateReturn("forms", "form-users-admin") ?>" question="Indien dit vinkje aanstaat krijgt deze gebruiker de mogelijkheid om de autorisaties van zichzelf of anderen aan te passen. Zo kun je iemand aanmaken die wel gebruikers mag beheren maar niet mag bepalen wie-wat ziet." />
				<?php
			}
			?>
			
			<input type="password" name="password" id="password" value="" class="width-300 margin" holder="<?= $mb->_translateReturn("forms", "form-users-password") ?>" validation-type="password" new-password="<?= isset($_GET['dataID']) ? 0 : 1 ?>" autocomplete="new-password" />
			<input type="password" name="password_repeat" id="password_repeat" value="" class="width-300 double-margin" holder="<?= $mb->_translateReturn("forms", "form-users-password-repeat") ?>" />
			
			<input type="file" name="profile_picture" id="profile_picture" value="" class="width-300 margin" holder="<?= $mb->_translateReturn("forms", "form-users-picture") ?>" holder-eg="<?= $mb->_translateReturn("forms", "form-users-picture-eg") ?>" validation-type="image" image-width="400" image-height="400" image-extension="png" />
			
			<select name="language_pack" id="language_pack" class="width-300" holder="<?= $mb->_translateReturn("forms", "form-language-pack") ?>">
				<option value="nl">Standaard Nederlands</option>
			</select>
		</div>
			
		
		<div class="form-content">
			<div class="content-header">
				<span class="fa fa-pencil-square-o"></span>
				<?= $mb->_translateReturn("forms", "legend-autorisations") ?>
			</div>
			
			<select name="start_page" id="start_page" class="width-300 double-margin" holder="<?= $mb->_translateReturn("forms", "form-users-startpage") ?>" question="Na het inloggen komt deze gebruiker hier terecht. U kunt dit aanpassen omdat u bijvoorbeeld niet wilt dat klanten in de winkel kunnen meekijken op uw dashboard wanneer u inlogt.">
				<?php
				if($mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "DAS", 0)))
				{
					?>
					<option <?= isset($_GET['dataID']) && $data['start_page'] == "/dashboard/view/" ? "selected=\"selected\"" : "" ?> value="/dashboard/view/">Dashboard</option>
					<?php
				}
				
				if($mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "VER_OB", 0)))
				{
					?>
					<option <?= isset($_GET['dataID']) && $data['start_page'] == "/verkoop/openstaand/" ? "selected=\"selected\"" : "" ?> value="/verkoop/openstaand/">Verkoop beheer > Openstaande bestellingen</option>
					<?php
				}
				
				if($mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "CAT_WC", 0)))
				{
					?>
					<option <?= isset($_GET['dataID']) && $data['start_page'] == "/catalogus/artikelen/" ? "selected=\"selected\"" : "" ?> value="/catalogus/artikelen/">Catalogus beheer > Artikelen beheren</option>
					<?php
				}
				
				if($mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "TPB_PT", 0)))
				{
					?>
					<option <?= isset($_GET['dataID']) && $data['start_page'] == "/cms/content/" ? "selected=\"selected\"" : "" ?> value="/cms/content/">Teksten en pagina beheer > Pagina teksten beheren</option>
					<?php
				}
				?>
			</select>
			
			<?php
			if($_SESSION['administrator'])
			{
				?>
				<select multiple name="authorization[]" id="authorization" class="multiselect width-300" holder="<?= $mb->_translateReturn("forms", "form-users-authorisations") ?>" holder-eg="<?= $mb->_translateReturn("forms", "form-users-authorisations-eg") ?>">
					<?php
					$valids = array(
						"DAS" => $mb->_translateReturn("menu", "dashboard"),
						"EMS" => $mb->_translateReturn("menu", "mailserver"),
						"VER" => $mb->_translateReturn("menu", "sales"),
						"VER_OB" => "&nbsp;&nbsp;&nbsp;&nbsp;>&nbsp;" . $mb->_translateReturn("menu", "open-orders"),
						"VER_AB" => "&nbsp;&nbsp;&nbsp;&nbsp;>&nbsp;" . $mb->_translateReturn("menu", "closed-orders"),
						"VER_RG" => "&nbsp;&nbsp;&nbsp;&nbsp;>&nbsp;" . $mb->_translateReturn("menu", "canceled-orders"),
						"VER_DC" => "&nbsp;&nbsp;&nbsp;&nbsp;>&nbsp;" . $mb->_translateReturn("menu", "debi-credi"),
						"VER_BG" => "&nbsp;&nbsp;&nbsp;&nbsp;>&nbsp;" . $mb->_translateReturn("menu", "paylink"),
						"CAT" => $mb->_translateReturn("menu", "catalog"),
						"CAT_WC" => "&nbsp;&nbsp;&nbsp;&nbsp;>&nbsp;" . $mb->_translateReturn("menu", "webshop-categories"),
						"CAT_AB" => "&nbsp;&nbsp;&nbsp;&nbsp;>&nbsp;" . $mb->_translateReturn("menu", "article-management"),
						"CAT_RB" => "&nbsp;&nbsp;&nbsp;&nbsp;>&nbsp;" . $mb->_translateReturn("menu", "reviews-management"),
						"VRB" => $mb->_translateReturn("menu", "stock"),
						"VRB_VM" => "&nbsp;&nbsp;&nbsp;&nbsp;>&nbsp;" . $mb->_translateReturn("menu", "stock-mutations"),
						"VRB_LB" => "&nbsp;&nbsp;&nbsp;&nbsp;>&nbsp;" . $mb->_translateReturn("menu", "manage-locations"),
						"DSB" => $mb->_translateReturn("menu", "dropshipping-accounts"),
						"DIS" => $mb->_translateReturn("menu", "promotions"),
						"DIS_CK" => "&nbsp;&nbsp;&nbsp;&nbsp;>&nbsp;" . $mb->_translateReturn("menu", "catalog-discounts"),
						"DIS_WK" => "&nbsp;&nbsp;&nbsp;&nbsp;>&nbsp;" . $mb->_translateReturn("menu", "cart-discounts"),
						"CUS" => $mb->_translateReturn("menu", "customers"),
						"TPB" => $mb->_translateReturn("menu", "cms"),
						"TPB_PT" => "&nbsp;&nbsp;&nbsp;&nbsp;>&nbsp;" . $mb->_translateReturn("menu", "page-content"),
						"TPB_BB" => "&nbsp;&nbsp;&nbsp;&nbsp;>&nbsp;" . $mb->_translateReturn("menu", "manage-banners"),
						"TPB_FB" => "&nbsp;&nbsp;&nbsp;&nbsp;>&nbsp;" . $mb->_translateReturn("menu", "image-gallery"),
						"TPB_ES" => "&nbsp;&nbsp;&nbsp;&nbsp;>&nbsp;" . $mb->_translateReturn("menu", "email-templates"),
						"TPB_SS" => "&nbsp;&nbsp;&nbsp;&nbsp;>&nbsp;" . $mb->_translateReturn("menu", "sms-templates"),
						"TPB_FK" => "&nbsp;&nbsp;&nbsp;&nbsp;>&nbsp;" . $mb->_translateReturn("menu", "invoice-content"),
						"POS" => $mb->_translateReturn("menu", "pos"),
						"POS_KM" => "&nbsp;&nbsp;&nbsp;&nbsp;>&nbsp;" . $mb->_translateReturn("menu", "pos-employees"),
						"POS_KI" => "&nbsp;&nbsp;&nbsp;&nbsp;>&nbsp;" . $mb->_translateReturn("menu", "pos-settings"),
						"POS_PI" => "&nbsp;&nbsp;&nbsp;&nbsp;>&nbsp;" . $mb->_translateReturn("menu", "pos-printers"),
						"WOR" => $mb->_translateReturn("menu", "workorders"),
						"WEB" => $mb->_translateReturn("menu", "website"),
						"RAP" => $mb->_translateReturn("menu", "reports"),
						"RAP_AO" => "&nbsp;&nbsp;&nbsp;&nbsp;>&nbsp;" . $mb->_translateReturn("menu", "articles-groups"),
						"RAP_AM" => "&nbsp;&nbsp;&nbsp;&nbsp;>&nbsp;" . $mb->_translateReturn("menu", "articles-brands"),
						"RAP_WA" => "&nbsp;&nbsp;&nbsp;&nbsp;>&nbsp;" . $mb->_translateReturn("menu", "week-register"),
						"RAP_MA" => "&nbsp;&nbsp;&nbsp;&nbsp;>&nbsp;" . $mb->_translateReturn("menu", "month-register"),
						"RAP_KC" => "&nbsp;&nbsp;&nbsp;&nbsp;>&nbsp;" . $mb->_translateReturn("menu", "register-check"),
						"RAP_GU" => "&nbsp;&nbsp;&nbsp;&nbsp;>&nbsp;" . $mb->_translateReturn("menu", "payment-book"),
						"RAP_VB" => "&nbsp;&nbsp;&nbsp;&nbsp;>&nbsp;" . $mb->_translateReturn("menu", "calculate-stock"),
						"SET" => $mb->_translateReturn("menu", "settings"),
						"SET_GB" => "&nbsp;&nbsp;&nbsp;&nbsp;>&nbsp;" . $mb->_translateReturn("menu", "manage-users"),
						"SET_BB" => "&nbsp;&nbsp;&nbsp;&nbsp;>&nbsp;" . $mb->_translateReturn("menu", "order-statuses"),
						"SET_BM" => "&nbsp;&nbsp;&nbsp;&nbsp;>&nbsp;" . $mb->_translateReturn("menu", "payment-methods"),
						"SET_VB" => "&nbsp;&nbsp;&nbsp;&nbsp;>&nbsp;" . $mb->_translateReturn("menu", "shipping-methods"),
						"SET_VG" => "&nbsp;&nbsp;&nbsp;&nbsp;>&nbsp;" . $mb->_translateReturn("menu", "manage-groups"),
						"SET_MB" => "&nbsp;&nbsp;&nbsp;&nbsp;>&nbsp;" . $mb->_translateReturn("menu", "manage-brands"),
						"SET_BP" => "&nbsp;&nbsp;&nbsp;&nbsp;>&nbsp;" . $mb->_translateReturn("menu", "manage-taxes"),
					);
					
					foreach($valids AS $code => $desc)
					{
						if($mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], $code, 0)) || (isset($_GET['dataID']) && $data['admin']))
						{
							?>
							<option <?= $mb->_runFunction("authorization", "userPermission", array($_GET['dataID'], $code, 0)) ? "selected=\"selected\"" : "" ?> value="<?= $code ?>"><?= $desc ?></option>
							<?php
						}
					}
					?>
				</select>
				<?php
			}
			?>
		</div>
		
	</div>
</form>
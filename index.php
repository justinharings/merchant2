<?php
// Start session

if(!isset($_SESSION))
{
	session_start();
}



/*
**	A build-in option to remove all of the
**	stored sessions in order to reset tests.
*/

$_reset = false;

if($_reset == true)
{
	foreach($_SESSION AS $key => $value)
	{
		unset($_SESSION[$key]);
	}
}



/*
**	Tell the classes and functions if the development
**	mode is activated or not. This will allow the classes
**	to display a user-friendly message or the real 
**	PHP exception for the developer.
*/

define("_DEVELOPMENT_ENVIRONMENT", true);
$_SESSION['_DEVELOPMENT_ENVIRONMENT'] = _DEVELOPMENT_ENVIRONMENT;



/*
**	Functions are added here. Used for quick access to all
**	of the extended special functions, all the files
**	are added to the core here.
*/

require_once(__DIR__ . "/library/php/functions/arrays.php");
require_once(__DIR__ . "/library/php/functions/floats.php");
require_once(__DIR__ . "/library/php/functions/text.php");



/*
**	Routers are used for redirecting people to
**	the right part of the website. They may change
**	some settings before redirecting.
*/

require_once(__DIR__ . "/library/php/routers/language.php");



/*
**	Classes are included here. We use a motherboard
**	class that is able to construct all the classes
**	and is able to run this class his function.
*/

require_once(__DIR__ . "/library/php/classes/motherboard.php");

$mb = new motherboard();



/*
**	Include the required third-party software.
**	Each software package includes a autoload.php
**	file that is requiring all of the needed
**	packages. If there is no autoload, a error is displayed.
*/

$mb->_requireThirdParty("minify-master");
$mb->_requireThirdParty("path-converter");



/*
**	If requested by the administrator (using the querystring /?minify),
**	the CSS and javascript files are made smaller in order for the
**	webshops performance to increase.
*/

use MatthiasMullie\Minify;

if(isset($_GET['minify']) || _DEVELOPMENT_ENVIRONMENT)
{
	$sourcePath = $_SERVER['DOCUMENT_ROOT'] . '/library/css/motherboard.css';
	$savePath = $_SERVER['DOCUMENT_ROOT'] . '/library/css/motherboard.minified.css';
	
	$minifier = new Minify\CSS();
	$minifier->add($sourcePath);
	$minifier->minify($savePath);
	
	
	$sourcePath = $_SERVER['DOCUMENT_ROOT'] . '/library/js/motherboard.js';
	$savePath = $_SERVER['DOCUMENT_ROOT'] . '/library/js/motherboard.minified.js';
	
	$minifier = new Minify\JS();
	$minifier->add($sourcePath);
	$minifier->minify($savePath);
	
	
	$sourcePath = $_SERVER['DOCUMENT_ROOT'] . '/library/js/input.js';
	$savePath = $_SERVER['DOCUMENT_ROOT'] . '/library/js/input.minified.js';
	
	$minifier = new Minify\JS();
	$minifier->add($sourcePath);
	$minifier->minify($savePath);
	
	
	$sourcePath = $_SERVER['DOCUMENT_ROOT'] . '/library/js/framework.js';
	$savePath = $_SERVER['DOCUMENT_ROOT'] . '/library/js/framework.minified.js';
	
	$minifier = new Minify\JS();
	$minifier->add($sourcePath);
	$minifier->minify($savePath);
	
	$sourcePath = $_SERVER['DOCUMENT_ROOT'] . '/library/js/multiselect.js';
	$savePath = $_SERVER['DOCUMENT_ROOT'] . '/library/js/multiselect.minified.js';
	
	$minifier = new Minify\JS();
	$minifier->add($sourcePath);
	$minifier->minify($savePath);
	
	$sourcePath = $_SERVER['DOCUMENT_ROOT'] . '/library/js/notes.js';
	$savePath = $_SERVER['DOCUMENT_ROOT'] . '/library/js/notes.minified.js';
	
	$minifier = new Minify\JS();
	$minifier->add($sourcePath);
	$minifier->minify($savePath);
	
	$sourcePath = $_SERVER['DOCUMENT_ROOT'] . '/library/js/emails.js';
	$savePath = $_SERVER['DOCUMENT_ROOT'] . '/library/js/emails.minified.js';
	
	$minifier = new Minify\JS();
	$minifier->add($sourcePath);
	$minifier->minify($savePath);
	
	$sourcePath = $_SERVER['DOCUMENT_ROOT'] . '/library/js/datepicker.js';
	$savePath = $_SERVER['DOCUMENT_ROOT'] . '/library/js/datepicker.minified.js';
	
	$minifier = new Minify\JS();
	$minifier->add($sourcePath);
	$minifier->minify($savePath);
	
	if(isset($_GET['minify']))
	{
		echo "Minify done.";
	}
}
?>

<!DOCTYPE html>
<html lang="<?= _LANGUAGE_PACK ?>">
	<head>
		<title><?= $mb->_runFunction("head", "title") ?></title>
		
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta http-equiv="Content-Language" content="<?= _LANGUAGE_PACK ?>" />
		
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
		
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
		<meta name="format-detection" content="telephone=no" />
		
		<meta name="robots" content="index, follow" />
		<meta name="description" content="<?= $mb->_runFunction("head", "description") ?>" />
		<meta name="keywords" content="<?= $mb->_runFunction("head", "keywords") ?>" />

		<link rel="apple-touch-icon" href="/library/media/apple-icon.png" />
		<link type="image/x-icon" rel="icon" href="/library/media/favicon.png" />
		<link type="image/x-icon" rel="shortcut icon" href="/library/media/favicon.png" />
		
		<link rel="stylesheet" type="text/css" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" />
		<link rel="stylesheet" type="text/css" href="/library/css/motherboard.minified.css" />

		<script type="text/javascript" src="//code.jquery.com/jquery-latest.js"></script>
		<script type="text/javascript" src="/library/js/motherboard.minified.js"></script>
	</head>

	<body>
		<div class="loader"><span class="fa fa-spinner fa-spin"></span></div>
		<img src="/library/media/orientation.png" class="orientation" />
		
		<?php
		if($mb->_runFunction("authorization", "validateLogin"))
		{
			?>
			<div class="menu">
				<div class="menu-header">
					<a href="/<?= _LANGUAGE_PACK ?>/">
						<img src="/library/media/logo_light.png" class="logo" />
					</a>

					<img src="<?= $mb->_runFunction("users", "returnProfileImage", array($_SESSION['userID'])) ?>" class="profile hide-mobile" />
					<span class="fa fa-bars show-mobile"></span>
				</div>
				
				<div class="menu-items-holder">
					<ul class="menu">
						<?php
						if($mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "DAS", 0)))
						{
							?>
							<li class="menu-item" rel="/dashboard/view/">
								<div class="text"><?= $mb->_translateReturn("menu", "dashboard") ?></div>
								<div class="sub"><?= $mb->_translateReturn("menu", "dashboard-eg") ?></div>
								
								<div class="icon">
									<span class="fa fa-area-chart"></span>
								</div>
							</li>
							<?php
						}
						
						if($mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "EMS", 0)))
						{
							?>
							<li class="menu-item" rel="/email-server/view/">
								<div class="text"><?= $mb->_translateReturn("menu", "mailserver") ?></div>
								<div class="sub"><?= $mb->_translateReturn("menu", "mailserver-eg") ?></div>
								
								<div class="icon">
									<span class="fa fa-server"></span>
								</div>
							</li>
							<?php
						}
						
						if($mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "VER", 0)))
						{
							?>
							<li class="parent menu-item">
								<div class="text"><?= $mb->_translateReturn("menu", "sales") ?></div>
								<div class="sub"><?= $mb->_translateReturn("menu", "sales-eg") ?></div>
								
								<div class="icon">
									<span class="fa fa-shopping-cart"></span>
								</div>
							</li>
							
							<li class="submenu">
								<ul>
									<?php
									if($mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "VER_OB", 0)))
									{
										?>
										<li class="menu-item" rel="/verkoop/openstaand/">
											<div class="text"><?= $mb->_translateReturn("menu", "open-orders") ?></div>
											
											<div class="icon">
												<span class="textual"><?= $mb->_translateReturn("menu", "open-orders-abbr") ?></span>
											</div>
										</li>
										<?php
									}
									
									if($mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "VER_AB", 0)))
									{
										?>
										<li class="menu-item" rel="/verkoop/afgerond/">
											<div class="text"><?= $mb->_translateReturn("menu", "closed-orders") ?></div>
											
											<div class="icon">
												<span class="textual"><?= $mb->_translateReturn("menu", "closed-orders-abbr") ?></span>
											</div>
										</li>
										<?php
									}
									
									if($mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "VER_RG", 0)))
									{
										?>
										<li class="menu-item" rel="/verkoop/geweigerd/">
											<div class="text"><?= $mb->_translateReturn("menu", "canceled-orders") ?></div>
											
											<div class="icon">
												<span class="textual"><?= $mb->_translateReturn("menu", "canceled-orders-abbr") ?></span>
											</div>
										</li>
										<?php
									}
									
									if($mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "VER_DC", 0)))
									{
										?>
										<li class="menu-item" rel="/verkoop/debcred/">
											<div class="text"><?= $mb->_translateReturn("menu", "debi-credi") ?></div>
											
											<div class="icon">
												<span class="textual"><?= $mb->_translateReturn("menu", "debi-credi-abbr") ?></span>
											</div>
										</li>
										<?php
									}
									
									if($mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "VER_BG", 0)))
									{
										?>
										<li class="menu-item" rel="/verkoop/betaallink/">
											<div class="text"><?= $mb->_translateReturn("menu", "paylink") ?></div>
											
											<div class="icon">
												<span class="textual"><?= $mb->_translateReturn("menu", "paylink-abbr") ?></span>
											</div>
										</li>
										<?php
									}
									?>
								</ul>
							</li>
							<?php
						}
						
						if($mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "CAT", 0)))
						{
							?>
							<li class="parent menu-item">
								<div class="text"><?= $mb->_translateReturn("menu", "catalog") ?></div>
								<div class="sub"><?= $mb->_translateReturn("menu", "catalog-eg") ?></div>
								
								<div class="icon">
									<span class="fa fa-tags"></span>
								</div>
							</li>
							
							<li class="submenu">
								<ul>
									<?php
									if($mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "CAT_WC", 0)))
									{
										?>
										<li class="menu-item" rel="/catalogus/categorieen/">
											<div class="text"><?= $mb->_translateReturn("menu", "webshop-categories") ?></div>
											
											<div class="icon">
												<span class="textual"><?= $mb->_translateReturn("menu", "webshop-categories-abbr") ?></span>
											</div>
										</li>
										<?php
									}
									
									if($mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "CAT_AB", 0)))
									{
										?>
										<li class="menu-item" rel="/catalogus/artikelen/">
											<div class="text"><?= $mb->_translateReturn("menu", "article-management") ?></div>
											
											<div class="icon">
												<span class="textual"><?= $mb->_translateReturn("menu", "article-management-abbr") ?></span>
											</div>
										</li>
										<?php
									}
									
									if($mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "CAT_RB", 0)))
									{
										?>
										<li class="menu-item" rel="/catalogus/reviews/">
											<div class="text"><?= $mb->_translateReturn("menu", "reviews-management") ?></div>
											
											<div class="icon">
												<span class="textual"><?= $mb->_translateReturn("menu", "reviews-management-abbr") ?></span>
											</div>
										</li>
										<?php
									}
									?>
								</ul>
							</li>
							<?php
						}
						
						if($mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "VRB", 0)))
						{
							?>
							<li class="parent menu-item">
								<div class="text"><?= $mb->_translateReturn("menu", "stock") ?></div>
								<div class="sub"><?= $mb->_translateReturn("menu", "stock-eg") ?></div>
								
								<div class="icon">
									<span class="fa fa-exchange"></span>
								</div>
							</li>
							
							<li class="submenu">
								<ul>
									<?php
									if($mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "VRB_VM", 0)))
									{
										?>
										<li class="menu-item" rel="/voorraad/mutaties/">
											<div class="text"><?= $mb->_translateReturn("menu", "stock-mutations") ?></div>
											
											<div class="icon">
												<span class="textual"><?= $mb->_translateReturn("menu", "stock-mutations-abbr") ?></span>
											</div>
										</li>
										<?php
									}
									
									if($mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "VRB_LB", 0)))
									{
										?>
										<li class="menu-item" rel="/voorraad/locaties/">
											<div class="text"><?= $mb->_translateReturn("menu", "manage-locations") ?></div>
											
											<div class="icon">
												<span class="textual"><?= $mb->_translateReturn("menu", "manage-locations-abbr") ?></span>
											</div>
										</li>
										<?php
									}
									
									if($mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "VRB_LV", 0)))
									{
										?>
										<li class="menu-item" rel="/voorraad/laag/">
											<div class="text"><?= $mb->_translateReturn("menu", "low-stock") ?></div>
											
											<div class="icon">
												<span class="textual"><?= $mb->_translateReturn("menu", "low-stock-abbr") ?></span>
											</div>
										</li>
										<?php
									}
									
									if($mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "VRB_GA", 0)))
									{
										?>
										<li class="menu-item" rel="/voorraad/gereserveerd/">
											<div class="text"><?= $mb->_translateReturn("menu", "reserved-articles") ?></div>
											
											<div class="icon">
												<span class="textual"><?= $mb->_translateReturn("menu", "reserved-articles-abbr") ?></span>
											</div>
										</li>
										<?php
									}
									?>
								</ul>
							</li>
							<?php
						}
						
						if($mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "DSB", 0)))
						{
							?>
							<li class="menu-item" rel="/dropshipping/view/">
								<div class="text"><?= $mb->_translateReturn("menu", "dropshipping-accounts") ?></div>
								<div class="sub"><?= $mb->_translateReturn("menu", "dropshipping-accounts-eg") ?></div>
								
								<div class="icon">
									<span class="fa fa-truck"></span>
								</div>
							</li>
							<?php
						}
						
						if($mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "DIS", 0)))
						{
							?>
							<li class="parent menu-item">
								<div class="text"><?= $mb->_translateReturn("menu", "promotions") ?></div>
								<div class="sub"><?= $mb->_translateReturn("menu", "promotions-eg") ?></div>
								
								<div class="icon">
									<span class="fa fa-star correction"></span>
								</div>
							</li>
							
							<li class="submenu">
								<ul>
									<?php
									if($mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "DIS_CK", 0)))
									{
										?>
										<li class="menu-item" rel="/promoties/catalogus/">
											<div class="text"><?= $mb->_translateReturn("menu", "catalog-discounts") ?></div>
											
											<div class="icon">
												<span class="textual"><?= $mb->_translateReturn("menu", "catalog-discounts-abbr") ?></span>
											</div>
										</li>
										<?php
									}
									
									if($mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "DIS_WK", 0)))
									{
										?>
										<li class="menu-item" rel="/promoties/winkelwagen/">
											<div class="text"><?= $mb->_translateReturn("menu", "cart-discounts") ?></div>
											
											<div class="icon">
												<span class="textual"><?= $mb->_translateReturn("menu", "cart-discounts-abbr") ?></span>
											</div>
										</li>
										<?php
									}
									?>
								</ul>
							</li>
							<?php
						}
						
						if($mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "CUS", 0)))
						{
							?>
							<li class="menu-item" rel="/klanten/view/">
								<div class="text"><?= $mb->_translateReturn("menu", "customers") ?></div>
								<div class="sub"><?= $mb->_translateReturn("menu", "customers-eg") ?></div>
								
								<div class="icon">
									<span class="fa fa-coffee"></span>
								</div>
							</li>
							<?php
						}
						
						if($mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "TPB", 0)))
						{
							?>
							<li class="parent menu-item">
								<div class="text"><?= $mb->_translateReturn("menu", "cms") ?></div>
								<div class="sub"><?= $mb->_translateReturn("menu", "cms-eg") ?></div>
								
								<div class="icon">
									<span class="fa fa-file-text-o"></span>
								</div>
							</li>
							
							<li class="submenu">
								<ul>
									<?php
									if($mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "TPB_PT", 0)))
									{
										?>
										<li class="menu-item" rel="/cms/content/">
											<div class="text"><?= $mb->_translateReturn("menu", "page-content") ?></div>
											
											<div class="icon">
												<span class="textual"><?= $mb->_translateReturn("menu", "page-content-abbr") ?></span>
											</div>
										</li>
										<?php
									}
									
									if($mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "TPB_BB", 0)))
									{
										?>
										<li class="menu-item" rel="/cms/banners/">
											<div class="text"><?= $mb->_translateReturn("menu", "manage-banners") ?></div>
											
											<div class="icon">
												<span class="textual"><?= $mb->_translateReturn("menu", "manage-banners-abbr") ?></span>
											</div>
										</li>
										<?php
									}
									
									if($mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "TPB_FB", 0)))
									{
										?>
										<li class="menu-item" rel="/cms/fotoalbum/">
											<div class="text"><?= $mb->_translateReturn("menu", "image-gallery") ?></div>
											
											<div class="icon">
												<span class="textual"><?= $mb->_translateReturn("menu", "image-gallery-abbr") ?></span>
											</div>
										</li>
										<?php
									}
									
									if($mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "TPB_ES", 0)))
									{
										?>
										<li class="menu-item" rel="/cms/emails/">
											<div class="text"><?= $mb->_translateReturn("menu", "email-templates") ?></div>
											
											<div class="icon">
												<span class="textual"><?= $mb->_translateReturn("menu", "email-templates-abbr") ?></span>
											</div>
										</li>
										<?php
									}
									
									if($mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "TPB_SS", 0)))
									{
										?>
										<li class="menu-item" rel="/cms/sms/">
											<div class="text"><?= $mb->_translateReturn("menu", "sms-templates") ?></div>
											
											<div class="icon">
												<span class="textual"><?= $mb->_translateReturn("menu", "sms-templates-abbr") ?></span>
											</div>
										</li>
										<?php
									}
									
									if($mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "TPB_FK", 0)))
									{
										?>
										<li class="menu-item" rel="/cms/facturatie/">
											<div class="text"><?= $mb->_translateReturn("menu", "invoice-content") ?></div>
											
											<div class="icon">
												<span class="textual"><?= $mb->_translateReturn("menu", "invoice-content-abbr") ?></span>
											</div>
										</li>
										<?php
									}
									?>
								</ul>
							</li>
							<?php
						}
						
						if($mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "POS", 0)))
						{
							?>
							<li class="parent menu-item">
								<div class="text"><?= $mb->_translateReturn("menu", "pos") ?></div>
								<div class="sub"><?= $mb->_translateReturn("menu", "pos-eg") ?></div>
								
								<div class="icon">
									<span class="fa fa-calculator"></span>
								</div>
							</li>
							
							<li class="submenu">
								<ul>
									<?php
									if($mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "POS_KM", 0)))
									{
										?>
										<li class="menu-item" rel="/pos/medewerkers/">
											<div class="text"><?= $mb->_translateReturn("menu", "pos-employees") ?></div>
											
											<div class="icon">
												<span class="textual"><?= $mb->_translateReturn("menu", "pos-employees-abbr") ?></span>
											</div>
										</li>
										<?php
									}
									
									if($mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "POS_KI", 0)))
									{
										?>
										<li class="menu-item" rel="/pos/instellingen/">
											<div class="text"><?= $mb->_translateReturn("menu", "pos-settings") ?></div>
											
											<div class="icon">
												<span class="textual"><?= $mb->_translateReturn("menu", "pos-settings-abbr") ?></span>
											</div>
										</li>
										<?php
									}
									
									if($mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "POS_PI", 0)))
									{
										?>
										<li class="menu-item" rel="/pos/printers/">
											<div class="text"><?= $mb->_translateReturn("menu", "pos-printers") ?></div>
											
											<div class="icon">
												<span class="textual"><?= $mb->_translateReturn("menu", "pos-printers-abbr") ?></span>
											</div>
										</li>
										<?php
									}
									?>
								</ul>
							</li>
							<?php
						}
						
						if($mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "WOR", 0)))
						{
							?>
							<li class="menu-item" rel="/werkorders/instellingen/">
								<div class="text"><?= $mb->_translateReturn("menu", "workorders") ?></div>
								<div class="sub"><?= $mb->_translateReturn("menu", "workorders-eg") ?></div>
								
								<div class="icon">
									<span class="fa fa-wrench"></span>
								</div>
							</li>
							<?php
						}
						
						if($mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "RAP", 0)))
						{
							?>
							<li class="parent menu-item">
								<div class="text"><?= $mb->_translateReturn("menu", "reports") ?></div>
								<div class="sub"><?= $mb->_translateReturn("menu", "reports-eg") ?></div>
								
								<div class="icon">
									<span class="fa fa-bar-chart"></span>
								</div>
							</li>
							
							<li class="submenu">
								<ul>
									<?php
									if($mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "RAP_AV", 0)))
									{
										?>
										<li class="menu-item" rel="/rapportages/verzendmethoden/">
											<div class="text"><?= $mb->_translateReturn("menu", "report-shipments") ?></div>
											
											<div class="icon">
												<span class="textual"><?= $mb->_translateReturn("menu", "report-shipments-abbr") ?></span>
											</div>
										</li>
										<?php
									}
									
									if($mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "RAP_AO", 0)))
									{
										?>
										<li class="menu-item" rel="/rapportages/omzetgroep/">
											<div class="text"><?= $mb->_translateReturn("menu", "articles-groups") ?></div>
											
											<div class="icon">
												<span class="textual"><?= $mb->_translateReturn("menu", "articles-groups-abbr") ?></span>
											</div>
										</li>
										<?php
									}
									
									if($mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "RAP_AM", 0)))
									{
										?>
										<li class="menu-item" rel="/rapportages/leverancier/">
											<div class="text"><?= $mb->_translateReturn("menu", "articles-brands") ?></div>
											
											<div class="icon">
												<span class="textual"><?= $mb->_translateReturn("menu", "articles-brands-abbr") ?></span>
											</div>
										</li>
										<?php
									}
									
									if($mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "RAP_GU", 0)))
									{
										?>
										<li class="menu-item" rel="/rapportages/grootboek/">
											<div class="text"><?= $mb->_translateReturn("menu", "payment-book") ?></div>
											
											<div class="icon">
												<span class="textual"><?= $mb->_translateReturn("menu", "payment-book-abbr") ?></span>
											</div>
										</li>
										<?php
									}
									
									if($mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "RAP_VB", 0)))
									{
										?>
										<li class="menu-item" rel="/rapportages/voorraden/">
											<div class="text"><?= $mb->_translateReturn("menu", "calculate-stock") ?></div>
											
											<div class="icon">
												<span class="textual"><?= $mb->_translateReturn("menu", "calculate-stock-abbr") ?></span>
											</div>
										</li>
										<?php
									}
									?>
								</ul>
							</li>
							<?php
						}
						
						if($mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "SET", 0)))
						{
							?>
							<li class="parent menu-item">
								<div class="text"><?= $mb->_translateReturn("menu", "settings") ?></div>
								<div class="sub"><?= $mb->_translateReturn("menu", "settings-eg") ?></div>
								
								<div class="icon">
									<span class="fa fa-cog correction"></span>
								</div>
							</li>
							
							<li class="submenu">
								<ul>
									<?php
									if($mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "SET_GB", 0)))
									{
										?>
										<li class="menu-item" rel="/instellingen/gebruikers/">
											<div class="text"><?= $mb->_translateReturn("menu", "manage-users") ?></div>
											
											<div class="icon">
												<span class="textual"><?= $mb->_translateReturn("menu", "manage-users-abbr") ?></span>
											</div>
										</li>
										<?php
									}
									
									if($mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "SET_BB", 0)))
									{
										?>
										<li class="menu-item" rel="/instellingen/bestelstatussen/">
											<div class="text"><?= $mb->_translateReturn("menu", "order-statuses") ?></div>
											
											<div class="icon">
												<span class="textual"><?= $mb->_translateReturn("menu", "order-statuses-abbr") ?></span>
											</div>
										</li>
										<?php
									}
									
									if($mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "SET_BM", 0)))
									{
										?>
										<li class="menu-item" rel="/instellingen/betalingsmethoden/">
											<div class="text"><?= $mb->_translateReturn("menu", "payment-methods") ?></div>
											
											<div class="icon">
												<span class="textual"><?= $mb->_translateReturn("menu", "payment-methods-abbr") ?></span>
											</div>
										</li>
										<?php
									}
									
									if($mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "SET_VB", 0)))
									{
										?>
										<li class="menu-item" rel="/instellingen/verzendmethoden/">
											<div class="text"><?= $mb->_translateReturn("menu", "shipping-methods") ?></div>
											
											<div class="icon">
												<span class="textual"><?= $mb->_translateReturn("menu", "shipping-methods-abbr") ?></span>
											</div>
										</li>
										<?php
									}
									
									if($mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "SET_VG", 0)))
									{
										?>
										<li class="menu-item" rel="/instellingen/groepen/">
											<div class="text"><?= $mb->_translateReturn("menu", "manage-groups") ?></div>
											
											<div class="icon">
												<span class="textual"><?= $mb->_translateReturn("menu", "manage-groups-abbr") ?></span>
											</div>
										</li>
										<?php
									}
									
									if($mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "SET_MB", 0)))
									{
										?>
										<li class="menu-item" rel="/instellingen/merken/">
											<div class="text"><?= $mb->_translateReturn("menu", "manage-brands") ?></div>
											
											<div class="icon">
												<span class="textual"><?= $mb->_translateReturn("menu", "manage-brands-abbr") ?></span>
											</div>
										</li>
										<?php
									}
									
									if($mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "SET_BP", 0)))
									{
										?>
										<li class="menu-item" rel="/instellingen/belastingen/">
											<div class="text"><?= $mb->_translateReturn("menu", "manage-taxes") ?></div>
											
											<div class="icon">
												<span class="textual"><?= $mb->_translateReturn("menu", "manage-taxes-abbr") ?></span>
											</div>
										</li>
										<?php
									}
									?>
								</ul>
							</li>
							<?php
						}
						?>
						
						<li class="menu-item" rel="/releases/view/">
							<div class="text"><?= $mb->_translateReturn("menu", "release-notes") ?></div>
							<div class="sub"><?= $mb->_translateReturn("menu", "release-notes-eg") ?></div>
							
							<div class="icon">
								<span class="fa fa-leaf"></span>
							</div>
						</li>
					</ul>
				</div>
			</div>
			
			<div class="top">
				<span class="fa fa-power-off logout-button"></span>
				<span class="fa fa-history previous-button"></span>
			</div>
			
			<div class="content">
				<?php
				if	(
						isset($_GET['form']) && file_exists($_SERVER['DOCUMENT_ROOT'] . "/modules/" . $_GET['module'] . "/" . str_replace("/", "", $_GET['form']) . ".php")
					)
				{
					require_once($_SERVER['DOCUMENT_ROOT'] . "/modules/" . $_GET['module'] . "/" . str_replace("/", "", $_GET['form']) . ".php");
				}
				else
				{
					if(!isset($_GET['form']) && file_exists($_SERVER['DOCUMENT_ROOT'] . "/modules/" . $_GET['module'] . "/" . str_replace("/", "", $_GET['file']) . ".php"))
					{
						require_once($_SERVER['DOCUMENT_ROOT'] . "/modules/" . $_GET['module'] . "/" . str_replace("/", "", $_GET['file']) . ".php");
					}
					else
					{
						require_once($_SERVER['DOCUMENT_ROOT'] . "/modules/errors/404.php");
					}
				}
				?>
			</div>
			<?php
		}
		else
		{
			require_once(__DIR__ . "/modules/authorization/login.php");
		}
		?>
		
		<input type="hidden" name="_language_pack" id="_language_pack" value="<?= _LANGUAGE_PACK ?>" />
	</body>
</html>
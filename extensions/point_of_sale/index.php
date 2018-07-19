<?php
// Start session

if(!isset($_SESSION))
{
	session_start();
}


/*
**	POS Only operates in the NL language pack.
*/

define("_LANGUAGE_PACK", "nl");



/*
**	Tell the classes and functions if the development
**	mode is activated or not. This will allow the classes
**	to display a user-friendly message or the real 
**	PHP exception for the developer.
*/

$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

define("_DEVELOPMENT_ENVIRONMENT", (strpos($actual_link, "dev.") !== false ? true : false));
$_SESSION['_DEVELOPMENT_ENVIRONMENT'] = _DEVELOPMENT_ENVIRONMENT;



/*
**	Functions are added here. Used for quick access to all
**	of the extended special functions, all the files
**	are added to the core here.
*/

require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/functions/arrays.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/functions/floats.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/functions/text.php");



/*
**	Classes are included here. We use a motherboard
**	class that is able to construct all the classes
**	and is able to run this class his function.
*/

require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/classes/motherboard.php");

$mb = new motherboard();



if(isset($_SESSION['terminal']) && $_GET['module'] == "register")
{
	require_once(__DIR__ . "/library/php/posts/cart_reset.php");
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
		<link rel="stylesheet" type="text/css" href="/library/css/pos.css" />

		<script type="text/javascript" src="//code.jquery.com/jquery-latest.js"></script>
		<script type="text/javascript" src="/library/js/motherboard.minified.js"></script>
		<script type="text/javascript" src="/library/js/pos.js"></script>
	</head>

	<body>
		<div class="loader"><span class="fa fa-spinner fa-spin"></span></div>
		<img src="/library/media/orientation.png" class="orientation" />
		
		<div class="popup-overlay"></div>
		
		<div class="popup-container">
			<div class="closer"><span class="fa fa-times"></span></div>
			<iframe src="about:blank"></iframe>
		</div>
		
		<?php
		if($mb->_runFunction("authorization", "validateLoginPOS"))
		{
			?>
			<div class="menu">
				<div class="menu-header">
					<a href="/<?= _LANGUAGE_PACK ?>/">
						<img src="/library/media/logo_light.png" class="logo" />
					</a>
						
					<a href="/pos/modules/employees/">
						<?php
						$image = "/library/media/employee_pictures/" . $_SESSION['employeeID'] . ".png";
	
						if(!file_exists($_SERVER['DOCUMENT_ROOT'] . $image))
						{
							$image = "/library/media/employee_pictures/no-picture.png";
						}
						?>
						
						<img src="<?= $image ?>" class="profile" />
					</a>
				</div>
				
				<div class="menu-items-holder">
					<ul class="menu">
						<li class="menu-item pos" rel="/register/">
							<div class="icon">
								<span class="fa fa-shopping-bag"></span>
							</div>
						</li>
						
						<li class="menu-item pos" rel="/orders/">
							<div class="icon">
								<span class="fa fa-shopping-cart"></span>
							</div>
						</li>
						
						<li class="menu-item pos" rel="/parked/">
							<div class="icon">
								<span class="fa fa-history"></span>
							</div>
						</li>
						
						<li class="menu-item pos" rel="/products/">
							<div class="icon">
								<span class="fa fa-tags"></span>
							</div>
						</li>
						
						<li class="menu-item pos" rel="/customers/">
							<div class="icon">
								<span class="fa fa-coffee"></span>
							</div>
						</li>
						
						<li class="menu-item pos" rel="/terminal/">
							<div class="icon">
								<span class="fa fa-paper-plane-o"></span>
							</div>
						</li>
						
						<li class="menu-item pos" rel="/workorders/">
							<div class="icon">
								<span class="fa fa-wrench"></span>
							</div>
						</li>
						
						<?php
						$merchant = $mb->_runFunction("merchant", "load", array($_SESSION['merchantID']));
						$url = $merchant['website_url'];
						
						if($url != "" && strpos($url, "http") !== false)
						{
							?>					
							<li class="menu-item pos" popup="<?= $url ?>">
								<div class="icon">
									<span class="fa fa-globe correction"></span>
								</div>
							</li>
							<?php
						}
						?>
						
						<li class="menu-item pos close-register">
							<div class="icon">
								<span class="fa fa-file-text-o"></span>
							</div>
						</li>
						
						<li class="menu-item pos open-drawer">
							<div class="icon">
								<span class="fa fa-eject"></span>
							</div>
						</li>
					</ul>
				</div>
			</div>
			
			<div class="top">
				<span class="fa fa-power-off logout-button-pos"></span>
				
				<?php
				if(isset($_SESSION['print_button_workorder']) || isset($_SESSION['print_button_order']))
				{
					?>
					<span class="fa fa-print print-last" target="<?= isset($_SESSION['print_button_workorder']) ? "workorder" : "receipt" ?>" targetID="<?= isset($_SESSION['print_button_workorder']) ? $_SESSION['print_button_workorder'] : $_SESSION['print_button_order'] ?>"></span>
					<span class="print-last-circle"><?= isset($_SESSION['print_button_workorder']) ? "W" : "K" ?></span>
					<?php
				}
				?>
			</div>
			
			<div class="content">
				<?php
				if(!isset($_SESSION['employeeID']))
				{
					require_once(__DIR__ . "/modules/employees.php");
				}
				else
				{	
					if(isset($_GET['module']) && file_exists(__DIR__ . "/modules/" . $_GET['module'] . ".php"))
					{
						require_once(__DIR__ . "/modules/" . $_GET['module'] . ".php");
					}
					else if(!isset($_GET['module']) && file_exists(__DIR__ . "/modules/register.php"))
					{
						?>
						<script type="text/javascript">
							document.location.href = '/pos/modules/register/';
						</script>
						<?php
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
			require_once($_SERVER['DOCUMENT_ROOT'] . "/modules/authorization/login_pos.php");
		}
		
		if(isset($_SESSION['print_auto_active']) || isset($_GET['price']))
		{
			$settings = $mb->_runFunction("pos", "loadPrinterSettings", array($_SESSION['merchantID']));
			?>
			
			<script type="text/javascript">
				<?php
				if($settings['auto_receipt'] == 1 && !isset($_GET['price']))
				{
					?>
					window.open('/extensions/printserver/index.php?type=receipt&action=print&orderID=<?= $_SESSION['last_order'] ?>');
					<?php
				}
				
				if($settings['auto_invoice'] == 1 && !isset($_GET['price']))
				{
					?>
					window.open('/extensions/printserver/index.php?type=invoice&action=print&orderID=<?= $_SESSION['last_order'] ?>');
					<?php
				}
				
				if($settings['auto_picklist'] == 1 && !isset($_GET['price']))
				{
					?>
					window.open('/extensions/printserver/index.php?type=picklist&action=print&orderID=<?= $_SESSION['last_order'] ?>');
					<?php
				}
				
				if(isset($_GET['price']))
				{
					?>
					setTimeout(
						function()
						{
							$(".pos-button.fa-euro").trigger("click");
						}, 500
					);
					<?php
				}
				?>
			</script>
			<?php
				
			unset($_SESSION['print_auto_active']);
		}
		?>
		
		<input type="hidden" name="_language_pack" id="_language_pack" value="<?= _LANGUAGE_PACK ?>" />
	</body>
</html>
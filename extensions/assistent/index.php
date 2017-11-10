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
?>

<!DOCTYPE html>
<html lang="<?= _LANGUAGE_PACK ?>">
	<head>
		<title>Winkelassistent</title>
		
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta http-equiv="Content-Language" content="<?= _LANGUAGE_PACK ?>" />
		
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
		
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
		<meta name="format-detection" content="telephone=no" />
		
		<meta name="robots" content="no-index, no-follow" />

		<link rel="apple-touch-icon" href="/library/media/apple-icon.png" />
		<link type="image/x-icon" rel="icon" href="/library/media/favicon.png" />
		<link type="image/x-icon" rel="shortcut icon" href="/library/media/favicon.png" />
		
		<link rel="stylesheet" type="text/css" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" />
		<link rel="stylesheet" type="text/css" href="/library/css/motherboard.minified.css" />
		<link rel="stylesheet" type="text/css" href="/library/css/pos.css" />

		<script type="text/javascript" src="//code.jquery.com/jquery-latest.js"></script>
		<script type="text/javascript" src="/library/js/motherboard.minified.js"></script>
		<script type="text/javascript" src="/library/js/assistent.js"></script>
	</head>

	<body>
		<div class="loader"><span class="fa fa-spinner fa-spin"></span></div>
		<img src="/library/media/orientation.png" class="orientation" />
		
		<div class="popup-overlay"></div>
		
		<div class="popup-container">
			<div class="closer"><span class="fa fa-times"></span></div>
			<iframe src="about:blank"></iframe>
		</div>
		
		<div class="menu">
			<div class="menu-header">
				<a href="/<?= _LANGUAGE_PACK ?>/">
					<img src="/library/media/logo_light.png" class="logo" />
				</a>
					
				<img src="/library/media/profile_pictures/assistent.png" class="profile" />
			</div>
			
			<div class="menu-items-holder">
				<ul class="menu">
					<li class="menu-item assistent" rel="/articles/">
						<div class="icon">
							<span class="fa fa-tags"></span>
						</div>
					</li>
					
					<li class="menu-item assistent" rel="/soldout/">
						<div class="icon">
							<span class="fa fa-trash"></span>
						</div>
					</li>
					
					<li class="menu-item assistent" rel="/pricecheck/">
						<div class="icon">
							<span class="fa fa-euro"></span>
						</div>
					</li>
				</ul>
			</div>
		</div>
		
		<div class="content">
			<?php
			if(isset($_GET['module']) && file_exists(__DIR__ . "/modules/" . $_GET['module'] . ".php"))
			{
				require_once(__DIR__ . "/modules/" . $_GET['module'] . ".php");
			}
			else if(!isset($_GET['modules']))
			{
				?>
				<script type="text/javascript">
					document.location.href = '/assistent/modules/articles';
				</script>
				<?php
			}
			else
			{
				require_once($_SERVER['DOCUMENT_ROOT'] . "/modules/errors/404.php");
			}
			?>
		</div>
				
		<input type="hidden" name="_language_pack" id="_language_pack" value="<?= _LANGUAGE_PACK ?>" />
	</body>
</html>
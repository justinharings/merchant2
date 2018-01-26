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

define("_DEVELOPMENT_ENVIRONMENT", true);
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
						<li class="menu-item workshop" rel="/open/">
							<div class="icon">
								<span class="fa fa-exclamation-triangle"></span>
							</div>
						</li>
						
						<li class="menu-item workshop" rel="/hold/">
							<div class="icon">
								<span class="fa fa-clock-o"></span>
							</div>
						</li>
						
						<li class="menu-item workshop" rel="/done/">
							<div class="icon">
								<span class="fa fa-check correction"></span>
							</div>
						</li>
						
						<li class="menu-item workshop battery-test">
							<div class="icon">
								<span class="fa fa-plug correction"></span>
							</div>
						</li>
						
						<li class="menu-item workshop" rel="/documentation/">
							<div class="icon">
								<span class="fa fa-question-circle correction"></span>
							</div>
						</li>
						
						<?php
						$data_workorder = $mb->_runFunction("workorders", "loadSettings", array($_SESSION['merchantID']));
						
						if($data_workorder['radio'])
						{
							?>
							<li class="menu-item workshop">
								<div class="icon">
									<span class="fa fa-music"></span>
								</div>
							</li>
							<?php
						}
						?>
					</ul>
				</div>
			</div>
			
			<div class="top">
				<span class="fa fa-power-off logout-button-workshop"></span>
				<span class="fa fa-history previous-button"></span>
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
						require_once(__DIR__ . "/modules/register.php");
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
		?>
		
		<input type="hidden" name="_language_pack" id="_language_pack" value="<?= _LANGUAGE_PACK ?>" />
	</body>
</html>
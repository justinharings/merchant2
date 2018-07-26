<?php
// Start session

if(!isset($_SESSION))
{
	session_start();
}

define("_LANGUAGE_PACK", "nl");

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

if(isset($_GET['orderID']))
{
	$order = $mb->_runFunction("orders", "load", array($_GET['orderID']));
	
	unset($_SESSION['grand_total']);
	unset($_SESSION['payments']);
	unset($_SESSION['payed']);
	unset($_SESSION['statusID']);
	unset($_SESSION['orderID']);
	
	$_SESSION['terminal'] = true;
	
	$_SESSION['customer'] = $order['customerID'];
	$_SESSION['orderID'] = $order['orderID'];
	
	if($order['grand_total'] > $order['payed'])
	{
		$_SESSION['grand_total'] = $order['grand_total'];
		$_SESSION['payed'] = $order['payed'];
		$_SESSION['loaded_payed'] = $order['payed'];
	}
}
?>

<!DOCTYPE html>
<html lang="nl">
	<head>
		<link rel="stylesheet" type="text/css" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" />
		<link rel="stylesheet" type="text/css" href="/library/css/motherboard.minified.css" />
		<link rel="stylesheet" type="text/css" href="/library/css/pos.css" />

		<script type="text/javascript" src="//code.jquery.com/jquery-latest.js"></script>
		
		<script type="text/javascript" src="/library/js/dashboard.minified.js"></script>
		<script type="text/javascript" src="/library/js/datepicker.minified.js"></script>
		<script type="text/javascript" src="/library/js/emails.minified.js"></script>
		<script type="text/javascript" src="/library/js/framework.minified.js"></script>
		<script type="text/javascript" src="/library/js/input.minified.js"></script>
		<script type="text/javascript" src="/library/js/multiselect.minified.js"></script>
		<script type="text/javascript" src="/library/js/notes.minified.js"></script>
		<script type="text/javascript" src="/library/js/sms.minified.js"></script>
		
		<script type="text/javascript" src="/library/js/pos.js"></script>
	</head>

	<body class="popup">
		<span class="fa fa-spin fa-gear popup-loader"></span>
		
		<script type="text/javascript">
			<?php
			if($order['grand_total'] > $order['payed'])
			{
				?>
				popup(500, 180, '/extensions/point_of_sale/modules/popup_payment.php');
				<?php
			}
			else
			{
				?>
				popup(370, 210, "/extensions/point_of_sale/modules/popup_status.php");
				<?php
			}
			?>
		</script>
	</body>
</html>	
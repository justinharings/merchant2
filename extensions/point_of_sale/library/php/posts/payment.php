<?php
if(!isset($_SESSION))
{
	session_start();
}

if(!isset($_SESSION['grand_total']))
{
	$grand_total = 0;
	
	foreach($_SESSION['cart'] AS $cart)
	{
		$grand_total += ($cart['quantity']*$cart['price']);
	}
	
	$_SESSION['grand_total'] = $grand_total;
}


if(!isset($_SESSION['payments']))
{
	$_SESSION['payments'] = array();
}

$num = count($_SESSION['payments']);

$_POST['value'] = str_replace(",", ".", $_POST['value']);

$_SESSION['payments'][$num]['paymentID'] = $_POST['paymentID'];
$_SESSION['payments'][$num]['amount'] = $_POST['value'];
$_SESSION['payments'][$num]['cash'] = $_POST['cash'];


$payed = ($_SESSION['loaded_payed'] > 0 ? $_SESSION['loaded_payed'] : 0);

foreach($_SESSION['payments'] AS $payment)
{
	$payed += $payment['amount'];
}

$_SESSION['payed'] = $payed;
?>

<!DOCTYPE html>
<html lang="nl">
	<head>
		<link rel="stylesheet" type="text/css" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" />
		<link rel="stylesheet" type="text/css" href="/library/css/motherboard.minified.css" />
		<link rel="stylesheet" type="text/css" href="/library/css/pos.css" />

		<script type="text/javascript" src="//code.jquery.com/jquery-latest.js"></script>
		<script type="text/javascript" src="/library/js/motherboard.minified.js"></script>
		<script type="text/javascript" src="/library/js/pos.js"></script>
		
		<script type="text/javascript">
			<?php
			if($_SESSION['payed'] < $_SESSION['grand_total'])
			{
				?>
				popup(500, 180, '/extensions/point_of_sale/modules/popup_payment.php');
				<?php
			}
			else
			{
				if(isset($_SESSION['customer']))
				{
					?>
					popup(370, 210, "/extensions/point_of_sale/modules/popup_status.php");
					<?php
				}
				else
				{
					?>
					popup(500, 145, '/extensions/point_of_sale/modules/popup_order_finished.php');
					<?php
				}
			}
			?>
		</script>
	</head>

	<body class="popup">
		<span class="fa fa-spin fa-gear popup-loader"></span>
	</body>
</html>
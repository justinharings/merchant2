<?php
if(!isset($_SESSION))
{
	session_start();
}

$_SESSION['statusID'] = $_POST['statusID'];
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
			popup(500, 145, '/extensions/point_of_sale/modules/popup_order_finished.php');
		</script>
	</head>

	<body class="popup">
		<span class="fa fa-spin fa-gear popup-loader"></span>
	</body>
</html>
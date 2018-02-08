<!DOCTYPE html>
<html lang="nl">
	<head>
		<link rel="stylesheet" type="text/css" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" />
		<link rel="stylesheet" type="text/css" href="/library/css/motherboard.minified.css" />
		<link rel="stylesheet" type="text/css" href="/library/css/pos.css" />

		<script type="text/javascript" src="//code.jquery.com/jquery-latest.js"></script>
		<script type="text/javascript" src="/library/js/motherboard.minified.js"></script>
		<script type="text/javascript" src="/library/js/pos.js"></script>
	</head>

	<body class="popup">
		<span class="fa fa-spin fa-gear popup-loader"></span>
		
		<?php
		if(!isset($_SESSION))
		{
			session_start();
		}
		
		define("_LANGUAGE_PACK", "nl");
		define("_MERCHANT_ID", $_SESSION['merchantID']);
		
		require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/classes/motherboard.php");
		
		$mb = new motherboard();
		
		$query = sprintf(
			"	SELECT		batteries.*
				FROM		batteries
				WHERE		batteries.barcode = '%s'",
			$_POST['barcode']
		);
		$result = $mb->query($query);
		
		if($mb->num_rows($result) > 0)
		{
			$row = $mb->fetch_assoc($result);
			?>
			<script type="text/javascript">
				parent.popup(300, 250, "/extensions/workshop/modules/popup_battery_test.php?batteryID=<?= $row['batteryID'] ?>");
			</script>
			<?php
		}
		else
		{
			header("location: /extensions/workshop/modules/popup_battery_detection.php");
		}
		?>
	</body>
</html>	
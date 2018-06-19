<?php
if(!isset($_SESSION))
{
	session_start();
}

define("_LANGUAGE_PACK", "NL");
				
require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/classes/motherboard.php");

$mb = new motherboard();

if(!isset($_SESSION['merchantID']))
{
	?>
	
	<script type="text/javascript">
		window.opener.top.location.reload();
		setTimeout(function() { window.close(); }, 100);
	</script>
	
	<?php
	exit;
}

$data = $mb->_runFunction("reports", "closeRegister", array($_SESSION['merchantID'], $_GET['period']));


require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/functions/arrays.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/functions/floats.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/functions/text.php");

	
$today = date("d-m-Y");

$date  = new DateTime($today);

if($_GET['period'] == "yesterday")
{
	$interval = new DateInterval('P1D');
	$date->sub($interval); 
}
?>

<html>
	<head>
		<style type="text/css">
			body, table
			{
				font-family: verdana;
				font-size: 11px;
			}
		
			@media print
			{
				#header, #footer, #nav { display: none !important; } 
			}
			
			@page
			{
				margin-left: 0px;
				margin-right: 0px;
				margin-top: 0px;
				margin-bottom: 0px;
			}
			
			img.logo
			{
				width: 100%;
				
				margin: 25px 0px 0px 0px;
			}
		</style>
	</head>
	
	<body style="margin: 0px; padding: 0px;">
		<div class="container" style="width: 245px;">
			<img class="logo" src="/library/media/merchant_logos/<?= $_SESSION['merchantID'] ?>.png" /><br/>
			<br/>
			Uitdraai van <?= $date->format('d-m-Y') ?><br/>
			<br/>
			<?php
			$total = 0;
			
			foreach($data[0] AS $name => $amount)
			{
				?>
				<strong><?= $name ?></strong><br/>
				&euro;&nbsp;<?= _frontend_float($amount) ?><br/>
				<br/>
				<?php
					
				$total += $amount;
			}
			?>
			
			<strong>Totaal bedrag</strong><br/>
			&euro;&nbsp;<?= _frontend_float($total) ?><br/>
			<br/><br/><br/>
			<?php
			foreach($data[1] AS $group => $qty)
			{
				?>
				<strong><?= $group ?></strong><br/>
				<?= $qty ?> stuk(s)<br/>
				<br/>
				<?php
			}
			?>
		</div>
		
		<script type="text/javascript">
			window.print();
			setTimeout(function() { window.close(); }, 100);
		</script>
	</body>
</html>
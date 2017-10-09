<?php
if(!isset($_SESSION))
{
	session_start();
}

define("_LANGUAGE_PACK", $_SESSION['language_pack']);
	
require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/classes/motherboard.php");

$mb = new motherboard();
$data1 = $mb->_runfunction("merchant", "load", array($_SESSION['merchantID']));
$data2 = $mb->_runFunction("orders", "load", array($_GET['orderID']));
?>

<style type="text/css">
	*
	{
		font-size: 22px;
	}
	
	table 
	{
		border-collapse: collapse;
	}
	
	td
	{
		padding: 10px;
		
		border: 1px solid #000;
		border-collapse: collapse;
	}
</style>

<table width="100%">
	<tr>
		<td align="center">
			<table width="100%">
				<tr>
					<td>
						Referentie:<br/>
						Order #<?= $data2['order_reference'] ?>
					</td>
					
					<td align="center">
						<img src="/library/third-party/barcode-image/barcode.php?code=<?= $_GET['orderID'] ?>" />
					</td>
				</tr>
			</table>
		</td>
	</tr>
	
	<tr>
		<td>
			<table width="100%">
				<tr>
					<td width="150" align="center">
						<strong>Afzender<br/>pakket:</strong>
					</td>
					<td width="*">
						<?= $data1['company_name'] ?><br/>
						<?= $data1['address'] ?><br/>
						<?= $data1['zip_code'] ?> <?= $data1['city'] ?><br/>
						Nederland
					</td>
				</tr>
			</table>
		</td>
	</tr>
	
	<tr>
		<td>
			<table width="100%">
				<tr>
					<td width="150" align="center">
						<strong>Ontvanger<br/>pakket:</strong>
					</td>
					<td width="*">
						<?= $data2['customer']['name'] ?><br/>
						<?= $data2['customer']['address'] ?><br/>
						<?= $data2['customer']['zip_code'] ?> <?= $data2['customer']['city'] ?><br/>
						<?= $data2['customer']['country'] ?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<script type="text/javascript">
	setTimeout(
		function()
		{
			window.print();
			window.close();
		}, 500
	);
</script>
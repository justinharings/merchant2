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
	
	div.outer-box
	{
		width: calc(100% - 60px);
		height: 10px;
		
		padding: 20px;
		display: table;
		
		border: 10px solid #ccc;
	}
	
		div.outer-box img
		{
			height: 80px;
		}
		
		div.outer-box table
		{
			border-collapse: collapse;
		}
		
			div.outer-box table tr td
			{
				font-family: Tahoma;
				font-size: 20px;
			}
			
				div.outer-box table tr td small
				{
					font-size: 13px;
				}
				
					div.outer-box table tr td small strong
					{
						font-family: Tahoma;
						font-size: 13px;
					}
			
				div.outer-box table tr td strong
				{
					font-family: Tahoma;
					font-size: 20px;
				}
</style>

<div class="outer-box">
	<table width="100%">
		<tr>
			<td width="75%">
				<img src="/library/media/merchant_logos/<?= $data2['merchantID'] ?>.png" />
			</td>
			
			<td width="25%">
				<small>
					<strong>From:</strong><br/>
					<?= $data1['company_name'] ?><br/>
					<?= $data1['address'] ?><br/>
					<?= $data1['zip_code'] ?> <?= $data1['city'] ?><br/>
					the Netherlands
				</small>
			</td>
		</tr>
		
		<tr>
			<td colspan="2"><br/><br/></td>
		</tr>
		
		<tr>
			<td colspan="2" align="center">
				<strong>To: <?= $data2['customer']['name'] ?></strong><br/>
				<?= $data2['customer']['address'] ?><br/>
				<?= strtoupper(str_replace(" ", "", $data2['customer']['zip_code'])) ?> <?= $data2['customer']['city'] ?><br/>
				<?= $data2['customer']['country'] ?><br/>
				<br/><br/>
				<img src="/library/third-party/barcode-image/barcode.php?code=<?= $_GET['orderID'] ?>" />
			</td>
		</tr>
	</table>
</div>

<script type="text/javascript">
	setTimeout(
		function()
		{
			window.print();
			window.close();
		}, 500
	);
</script>
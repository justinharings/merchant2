<?php
if(!isset($_SESSION['merchantID']))
{
	print "<center>U dient eerst in te loggen in Merchant voordat u deze functie kunt gebruiken.</center>";
	exit;
}

	
	
$externalStockID = rand(1,4);

if($externalStockID == 1)
{
	// Popal Fietsen Nederland
	
	$array = file_get_contents("http://plm.popal.nl/webservice/channel/2");
	$array = json_decode($array, true);
	
	foreach($array AS $key => $values)
	{		
		foreach($values['products'] AS $key_p => $value_p)
		{
			$supplier_code = "";
			$barcode = "";
			$stock_value = "";
			$image_url = "";
			$price_purchase = "";
			$price_adviced = "";
			
			foreach($value_p['properties'] AS $key_pr => $value_pr)
			{
				if($value_pr['name']['nl_nl'] == "Barcode")
				{		
					$barcode = $value_pr['value'];
				}
	
				if($value_pr['name']['nl_nl'] == "Voorraad aanwezig")
				{
					$stock_value = ($value_pr['value'] == 1 ? 1 : 0);
				}
				
				if($value_pr['name']['nl_nl'] == "Productcode")
				{
					$supplier_code = $value_pr['value']['nl_nl'];
				}
				
				if($value_pr['name']['nl_nl'] == "Productafbeelding")
				{
					$image_url = "http://plm.popal.nl/" . $value_pr['value'];
				}
				
				if($value_pr['name']['nl_nl'] == "Dealerprijs (Exclusief BTW)")
				{
					$price_purchase = $value_pr['value'];
				}
				
				if($value_pr['name']['nl_nl'] == "Consumentenprijs")
				{
					$price_adviced = $value_pr['value'];
				}
			}
	
			if($barcode != "")
			{
				$query = sprintf(
					"	SELECT		assistent.barcode
						FROM		assistent
						WHERE		assistent.barcode = '%s'",
					$barcode
				);
				$result = $mb->query($query);
				
				$query2 = sprintf(
					"	SELECT		products.barcode
						FROM		products
						WHERE		products.barcode = '%s'",
					$barcode
				);
				$result2 = $mb->query($query2);
				
				if	(
						$supplier_code != ""
						&& $barcode != ""
						&& $image_url != ""
						&& $price_purchase != ""
						&& $mb->num_rows($result) == 0
						&& $mb->num_rows($result2) == 0
						&& count($stock) == 0
					)
				{
					$stock = array();
					
					$stock['supplier_code'] = $supplier_code;
					$stock['barcode'] = $barcode;
					$stock['stock'] = $stock_value;
					$stock['image_url'] = $image_url;
					$stock['price_purchase'] = $price_purchase;
					$stock['price_adviced'] = $price_adviced;
					$stock['delivery_days'] = 2;
			
					$num++;
				}
			}
		}
	}
}
else if($externalStockID == 2)
{
	// Juncker Bikeparts
	
	$ftp_server = "ftp.accell-group.com";
	$ftp_user = "e-dst-harings";
	$ftp_pass = "16d165bR";
	
	$file = "/ArticlesandStock/ARTPOSEXTR.csv";
	
	$filename = "ftp://" . $ftp_user . ":" . $ftp_pass . "@" . $ftp_server . $file;
	$file = fopen($filename, "r");
	
	while(($line = fgetcsv($file)) !== FALSE) 
	{
		$expl = explode(";", $line[0]);
		
		$supplier_code = $expl[2];
		$barcode = $expl[1];
		$price_purchase = 0;
		$price_adviced = 0;
		
		if($barcode != "" && $barcode != "EAN CODE       ")
		{
			$query = sprintf(
				"	SELECT		assistent.barcode
					FROM		assistent
					WHERE		assistent.barcode = '%s'",
				$barcode
			);
			$result = $mb->query($query);
			
			$query2 = sprintf(
				"	SELECT		products.barcode
					FROM		products
					WHERE		products.barcode = '%s'",
				$barcode
			);
			$result2 = $mb->query($query2);
			
			if	(
					$supplier_code != ""
					&& $barcode != ""
					&& $mb->num_rows($result) == 0
					&& $mb->num_rows($result2) == 0
					&& count($stock) == 0
				)
			{
				$stock = array();
				
				$stock['supplier_code'] = $supplier_code;
				$stock['barcode'] = $barcode;
				$stock['stock'] = $stock_value;
				$stock['image_url'] = "";
				$stock['price_purchase'] = $price_purchase;
				$stock['price_adviced'] = $price_adviced;
				$stock['delivery_days'] = 2;
		
				break;
			}
		}
	}
	
	fclose($file);
}
else if($externalStockID == 3)
{
	// Batavus, Sparta en Loekie
}
else if($externalStockID == 4)
{
	// Hoop Fietsen
}

if(count($stock) == 0)
{
	?>
	<center>Bezig met zoeken naar nieuwe artikelen...</center>
	
	<script type="text/javascript">
		setTimeout(
			function()
			{
				document.location.href = document.location.href;
			}, 1000
		);
	</script>
	<?php
}
else
{
	$stock['externalStockID'] = $externalStockID;
	?>
	<center>
		<strong>
			<?php
			switch($externalStockID)
			{
				case 1: print "Popal Fietsen Nederland"; break;
				case 2: print "Juncker Bike Parts"; break;
				case 3: print "Batavus, Sparta en Loekie"; break;
				case 4: print "Hoop Fietsen"; break;
			}
			?>
		</strong><br/>
		<br/>
		<?= $stock['supplier_code'] ?><br/>
		<?= $stock['barcode'] ?><br/>
		<?php
		if($stock['image_url'] != "")
		{
			?>
			<a href="<?= $stock['image_url'] ?>" target="_blank" style="color: #d00000;">
				<?= $stock['image_url'] ?>
			</a><br/>
			<Br/>
			<?php
		}
		?>
		12 inch - 450 pixels<br/>
		16 inch - 500 pixels<br/>
		20 inch - 600 pixels<br/>
		22 inch - 650 pixels<br/>
		24 inch - 650 pixels<br/>
		26 inch - 700 pixels<br/>
		28 inch - 850 pixels<br/>
		<br/>
		<?php
		switch($stock['stock'])
		{
			case 1:
				print "De leverancier heeft dit artikel op voorraad.";
			break;
			
			case 2:
				print "Dit product is niet op voorraad.";
			break;
		}
		
		unset($stock['image_url']);
		
		$_SESSION['stock'] = serialize($stock);
		?><br/>
		<br/>
		<a href='/assistent/modules/add-new-form/' style="color: #d00000;">Artikel toevoegen</a>
		&nbsp;&nbsp;&nbsp;
		<a href='/extensions/assistent/library/php/posts/skip-article.php?barcode=<?= $stock['barcode'] ?>' style="color: #d00000;">Artikel overslaan</a>
	</center>
	<?php
}
?>
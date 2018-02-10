<?php
if(!isset($_SESSION))
{
	session_start();
}


define("_LANGUAGE_PACK", "nl");

$_SERVER['DOCUMENT_ROOT'] = "/var/www/vhosts/justinharings.nl/dev.justinharings.nl";

require_once("/var/www/vhosts/justinharings.nl/dev.justinharings.nl/library/php/classes/motherboard.php");

$mb = new motherboard();

$query = sprintf(
	"	SELECT		products_pricecheck.*
		FROM		products_pricecheck"
);
$result = $mb->query($query);

$had = array();
$products = array();

while($row = $mb->fetch_assoc($result))
{
	// Verwijder de strings van fietsenwinkel.nl voor de zekerheid.
	if(strpos($row['website'], "?sqr=") !== false)
	{
		$website = $row['website'];
		$website = explode("?sqr=", $website);
		$website = $website[0];
		
		$query2 = sprintf(
			"	UPDATE		products_pricecheck
				SET			products_pricecheck.website = '%s'
				WHERE		products_pricecheck.website = '%s'",
			$website,
			$row['website']
		);
		$mb->query($query2);
		
		$row['website'] = $website;
	}
	
	$value = 0;
	
	if(isset($had[$row['website']]))
	{
		$value = $had[$row['website']];
	}
	else
	{
		$content = file_get_contents($row['website']);
		
		if($content != "")
		{
			if(strpos($content, '<meta property="product:price:amount" content="') !== false)
			{
				$explode = explode('<meta property="product:price:amount" content="', $content);
				$explode = explode('"', $explode[1]);
				
				$value = $explode[0];
				$value = str_replace(",", ".", $value);
			}
			else if(strpos($content, '<meta property="product:price" content="') !== false)
			{
				$explode = explode('<meta property="product:price" content="', $content);
				$explode = explode('"', $explode[1]);
				
				$value = $explode[0];
				$value = str_replace(",", ".", $value);
			}
			else if(strpos($content, '<meta property="price" content="') !== false)
			{
				$explode = explode('<meta property="price" content="', $content);
				$explode = explode('"', $explode[1]);
				
				$value = $explode[0];
				$value = str_replace(",", ".", $value);
			}
			else if(strpos($content, '<meta itemprop="price" content="') !== false)
			{
				$explode = explode('<meta itemprop="price" content="', $content);
				$explode = explode('"', $explode[1]);
				
				$value = $explode[0];
				$value = str_replace(",", ".", $value);
			}
			else if(strpos($content, '<div class="price-info">') !== false)
			{
				// Special for fietsenwinkel.nl
				
				$explode = explode('<div class="price-info">', $content);
				$explode = explode('<span class="price">', $explode[1]);
				$explode = explode('</span>', $explode[1]);
				
				$value = $explode[0];
				$value = str_replace("&euro;", "", $value);
				$value = str_replace("€", "", $value);
				$value = str_replace(" ", "", $value);
				$value = str_replace("&nbsp;", "", $value);
				$value = str_replace(".-", "", $value);
				$value = str_replace(",-", "", $value);
				$value = str_replace(",", ".", $value);
			}
			
			$had[$row['website']] = ($value > 0 ? $value : 0);
			
			$value = explode(".", $had[$row['website']]);
			
			if(count($value) > 2)
			{
				$had[$row['website']] = $value[0] . $value[1] . "." . $value[2];
			}
			
			$query = sprintf(
				"	UPDATE		products_pricecheck
					SET			products_pricecheck.price = '%.2f',
								products_pricecheck.date_update = NOW()
					WHERE		products_pricecheck.website = '%s'",
				$had[$row['website']],
				$row['website']
			);
			$mb->query($query);
			
			//print $row['productID'] . "<br/>" . $row['website'] . "<br/>" . $had[$row['website']] . "<br/><br/>";
		}
	}
	
	if($value > 0)
	{
		if(!isset($products[$row['productID']]) || !is_array($products[$row['productID']]))
		{
			$products[$row['productID']] = array();
		}
		
		$products[$row['productID']][] = number_format($had[$row['website']], 2, ".", "");
	}
}

$content = "";

foreach($products AS $productID => $values)
{
	$query3 = sprintf(
		"	SELECT		products.name,
						products.price,
						products.price_purchase
			FROM		products
			WHERE		products.productID = %d",
		$productID
	);
	$result3 = $mb->query($query3);
	$row3 = $mb->fetch_assoc($result3);	
	
	// print $row3['name'] . " (" . $productID . "):<br/>";
	
	// print "Inkoop: " . $row3['price_purchase'] . "<br/>";
	// print "Onze prijs: " . $row3['price'] . "<br/>";
	
	// print "<br/>Gevonden prijzen:<br/>";
	
	$lowest = 0;
	
	foreach($values AS $price)
	{
		if($lowest == 0 || $price < $lowest)
		{
			$lowest = $price;
		}
		
		// print $price . "<br/>";
	}
	
	// print "<br/>Laagste: " . $lowest . "<br/>";
	
	if($lowest != $row3['price'])
	{
		$content .= $row3['name'] . "<br/>";
		
		$inkoop = $row3['price_purchase'];
		$inkoop = ($inkoop + (($inkoop/100)*21));
		$inkoop = number_format($inkoop, 2, ".", "");
		
		// print "Inkoop + BTW: " . $inkoop . "<br/>";
		
		$minimum = number_format(ceil(($inkoop + 20)), 2, ".", "");
		// print "Minimale winst: " . $minimum . "<br/>";
		
		$nieuwe_prijs = $lowest;
		
		// print "Nieuwe prijs: " . $nieuwe_prijs;
		
		$notice = "";
		
		if($lowest < $minimum)
		{
			$nieuwe_prijs = $minimum;
			$notice = "<br/>De concurent heeft een lagere prijs van onze inkoop.";
			// print "!!!!";
		}
		
		$content .= "van ". $row3['price'] . " euro naar " . $nieuwe_prijs . " euro." . $notice . "<br/><br/>";
		
		$queryU = sprintf(
			"	UPDATE		products
				SET			products.price = '%.2f'
				WHERE		products.productID = %d",
			$nieuwe_prijs,
			$productID
		);
		$mb->query($queryU);
		
		// print "<br/>";
	}
	else
	{
		// print "Prijzen zijn gelijk.<Br/>";
	}
	
	// print "<br/><br/>";
}

print $content;

if($content != "")
{
	$content = str_replace("<br/>", "\n", $content);
	
	$to      = 'info@haringstweewielers.nl';
	$subject = 'Prijsaanpassingen';
	$message = $content;
	$headers = 'From: info@haringstweewielers.nl' . "\r\n" .
	    'Reply-To: info@haringstweewielers.nl' . "\r\n" .
	    'X-Mailer: PHP/' . phpversion();
	
	mail($to, $subject, $message, $headers);
}

exit;
?>
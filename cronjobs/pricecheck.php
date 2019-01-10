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
		FROM		products_pricecheck
		INNER JOIN	products ON products.productID = products_pricecheck.productID
		WHERE		products.deleted = 0"
);
$result = $mb->query($query);

$had = array();
$products = array();

while($row = $mb->fetch_assoc($result))
{
	/*
	**	Verwijder de strings van fietsenwinkel.nl voor de zekerheid.
	*/
	
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
		/*
		**	Strip de website en haal de prijs eruit.
		**	De prijs komt uit een serie van velden waar die
		**	vermoedelijk in staat.
		*/
		
		$content = file_get_contents($row['website']);
		
		if($content != "")
		{
			if(strpos($content, '<meta property="product:price:amount" content="') !== false)
			{
				$explode = explode('<meta property="product:price:amount" content="', $content);
				$explode = explode('"', $explode[1]);
				
				$value = $explode[0];
				$value = preg_replace("/[^0-9,.]/", "", $value);
				
				$value = str_replace(",", ".", $value);
			}
			else if(strpos($content, '<meta property="product:price" content="') !== false)
			{
				$explode = explode('<meta property="product:price" content="', $content);
				$explode = explode('"', $explode[1]);
				
				$value = $explode[0];
				$value = preg_replace("/[^0-9,.]/", "", $value);
				
				$value = str_replace(",", ".", $value);
			}
			else if(strpos($content, '<meta property="price" content="') !== false)
			{
				$explode = explode('<meta property="price" content="', $content);
				$explode = explode('"', $explode[1]);
				
				$value = $explode[0];
				$value = preg_replace("/[^0-9,.]/", "", $value);
				
				$value = str_replace(",", ".", $value);
			}
			else if(strpos($content, '<meta itemprop="price" content="') !== false)
			{
				$explode = explode('<meta itemprop="price" content="', $content);
				$explode = explode('"', $explode[1]);
				
				$value = $explode[0];
				$value = preg_replace("/[^0-9,.]/", "", $value);
				
				$value = str_replace(",", ".", $value);
			}
			else if(strpos($content, '"productPrice":') !== false)
			{
				$explode = explode('"productPrice":', $content);
				$explode = explode(',', $explode[1]);
				
				$value = $explode[0];
				$value = preg_replace("/[^0-9,.]/", "", $value);
				
				$value = str_replace(",", ".", $value);
			}
			else if(strpos($content, '<div class="price-info">') !== false)
			{
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
			
			$errono = ($row['errors']+1);
			
			$query = sprintf(
				"	UPDATE		products_pricecheck
					SET			products_pricecheck.price = '%.2f',
								products_pricecheck.date_update = NOW(),
								products_pricecheck.errors = %d
					WHERE		products_pricecheck.website = '%s'",
				$had[$row['website']],
				($had[$row['website']] == 0 ? $errono : 0),
				$row['website']
			);
			$mb->query($query);
		}
	}
	
	if($value > 0)
	{
		if(!isset($products[$row['productID']]) || !is_array($products[$row['productID']]))
		{
			$products[$row['productID']] = array();
		}
		
		$num = count($products[$row['productID']]);
		
		$products[$row['productID']][$num]['price'] = number_format($had[$row['website']], 2, ".", "");
		$products[$row['productID']][$num]['website'] = $row['website'];
		$products[$row['productID']][$num]['free_shipment'] = $row['free_shipment'];
		$products[$row['productID']][$num]['profit'] = $row['profit'];
	}
}


/*
**	Stel een e-mail op om de admin's te laten
**	weten dat er een pricecheck is geweest.
*/

$html_table = "";

foreach($products AS $productID => $values)
{
	/*
	**	Collect the current product data.
	*/
	
	$query3 = sprintf(
		"	SELECT		products.article_code,
						products.name,
						products.price,
						products.price_purchase,
						taxes.percentage
			FROM		products
			INNER JOIN	taxes ON taxes.taxesID = products.taxesID
			WHERE		products.productID = %d",
		$productID
	);
	$result3 = $mb->query($query3);
	$row3 = $mb->fetch_assoc($result3);
	
	$row3['price_purchase'] += ($row3['price_purchase']*($row3['percentage']/100));
	
	$minimum_price = $row3['price_purchase'] + $values[0]['profit'];
	
	
	/*
	**	Find the shipping costs and add them in the loop below.
	*/
	
	$sQuery = sprintf(
		"	SELECT		shipment_methods.price
			FROM		products
			INNER JOIN	shipment_methods ON shipment_methods.shipmentID = products.shipmentID
			WHERE		products.productID = %d",
		$productID
	);
	$sResult = $mb->query($sQuery);
	$sRow = $mb->fetch_assoc($sResult);
	
	
	/*
	**	Find the cheapest row that the others
	**	are giving us and use this one. If there's a
	**	non-working check but there's another above it,
	**	use the one that works.
	*/
	
	$prices = array();
	
	foreach($values AS $data)
	{
		if($sRow['price'] > 0 && $data['free_shipment'] == 1 && $data['price'] > 0)
		{
			$data['price'] -= $sRow['price'];
		}
		
		$prices[] = $data['price'];
	}
	
	$lowest = 0;
	sort($values);
	
	foreach($prices AS $price)
	{
		if($price == 0)
		{
			continue;
		}
		
		$lowest = $price;
		break;
	}
	
	
	/*
	**	Status bepalen van deze aanpassing
	**	OK: Geen bijzonderheden. Winstgevend verwerkt.
	**	W1: Waarschuwing. De concurrent gaat lager dan de minimale winst.
	**	W2: Waarschuwing. De concurrent gaat lager dan de inkoopsprijs.
	**	E1: Fout. Domeinnaam kent geen prijzen. Artikel overgeslagen.
	**	E2: Waarschuwing. Dit artikel heeft geen inkoopsprijs. Artikel overgeslagen.
	*/
	
	$process = true;
	$status = "OK";
	$color = "green";
	$new_price = $lowest;
	$note = "";
	
	if($new_price == 0)
	{
		$status = "E1";
		$new_price = "0.00";
		$color = "red";
		$note = "Vergelijken is niet mogelijk.";
		$process = false;
	}
	else if($row3['price_purchase'] == 0)
	{
		$status = "E2";
		$new_price = "0.00";
		$color = "red";
		$note = "Vergelijken is niet mogelijk.";
		$process = false;
	}
	else if($lowest < $row3['price_purchase'])
	{
		$status = "W2";
		$new_price = $minimum_price;
		$color = "orange";
		$note = "Minimale winst is &euro;" . $values[0]['profit'] . ".";
	}
	else if($lowest < $minimum_price)
	{
		$status = "W1";
		$new_price = $minimum_price;
		$color = "orange";
		$note = "Minimale winst is &euro;" . $values[0]['profit'] . ".";
	}
	
	
	
	/*
	**	Create the table that's shown in the e-mail.
	**	Column 1: Status
	**	Column 2: Article code
	**	Column 3: Article name
	**	Column 4: Purchase price INCL VAT
	**	Column 5: Cheepest price online
	**	Column 6: The new price
	**	Column 7: Notes
	*/
	
	$new_price = ceil($new_price);
	
	if(($new_price != $row3['price']) || $process == false)
	{
		$html_table .= "<tr>
			<td class='" . $color . "'>" . $status . "</td>
			<td class='" . ($color == "red" ? "error" : "") . "'>" . sprintf("%05d", $row3['article_code']) . "</td>
			<td class='" . ($color == "red" ? "error" : "") . "'>" . substr($row3['name'], 0, 15) . " ...</td>
			<td class='" . ($color == "red" ? "error" : "") . "'>" . number_format($row3['price_purchase'], 2) . " euro</td>
			<td class='" . ($color == "red" ? "error" : "") . "'>" . number_format($lowest, 2) . " euro</td>
			<td class='" . ($color == "red" ? "error" : "") . "'>" . number_format($new_price, 2) . " euro</td>
			<td>" . $note . "</td>
		</tr>";
	}
	
	
	/*
	**	Voeg de nieuwe gegevens toe aan de database
	*/
	
	if($process == true)
	{
		$queryU = sprintf(
			"	UPDATE		products
				SET			products.price = '%.2f'
				WHERE		products.productID = %d",
			$new_price,
			$productID
		);
		$mb->query($queryU);
	}
}


$query = sprintf(
	"	DELETE FROM		products_pricecheck
		WHERE			products_pricecheck.errors > 2"
);
$mb->query($query);


$content = file_get_contents(__DIR__ . "/pricecheck/template.html");
$content = str_replace("{{table}}", $html_table, $content);


print $content;


if($content != "")
{
	$to      = 'info@haringstweewielers.nl';
	$subject = 'Prijsaanpassingen';
	$message = $content;
	$headers = 'From: info@haringstweewielers.nl' . "\r\n" .
	    'Reply-To: info@haringstweewielers.nl' . "\r\n" .
	    'Content-type:text/html;charset=UTF-8' . "\r\n" .
	    'X-Mailer: PHP/' . phpversion();
	
	mail($to, $subject, $message, $headers);
}

exit;
?>
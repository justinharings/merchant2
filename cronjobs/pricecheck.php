<?php
if(!isset($_SESSION))
{
	session_start();
}

$start = time();

$_debug = false;
$max = 9999999999;
$cnt = 0;

define("_LANGUAGE_PACK", "nl");

$_SERVER['DOCUMENT_ROOT'] = "/var/www/vhosts/justinharings.nl/dev.justinharings.nl";

require_once("/var/www/vhosts/justinharings.nl/dev.justinharings.nl/library/php/classes/motherboard.php");
require_once("/var/www/vhosts/justinharings.nl/dev.justinharings.nl/library/third-party/simple-html-dom/simple_html_dom.php");



function scraping($url) 
{
	$html = file_get_html($url);
	
	if(!$html)
	{
		return 0;
	}
	
    $price = $html->find('meta[itemprop="price"]', 0)->content;
    
    if($price == "")
    {
		$price = $html->find('meta[property="product:price"]', 0)->content;
	}
    
    if($price == "")
    {
		$price = $html->find('meta[property="product:price:amount"]', 0)->content;
	}
    
    if($price == "")
    {
		$price = $html->find('span[itemprop="price"]', 0)->innertext;
	}
    
    if($price == "")
    {
		$price = $html->find('span[class="price"]', 0)->innertext;
	}
    
    $html->clear();
    unset($html);

	if($price != "")
	{
		$price = preg_replace("/[^0-9,.]/", "", $price);
		$price = str_replace(",", ".", $price);
	}
	else
	{
		return 0;
	}
	
    return number_format($price, 2, ".", "");
}



$mb = new motherboard();

$query = sprintf(
	"	DELETE 		products_pricecheck
		FROM		products_pricecheck
		INNER JOIN	products ON products.productID = products_pricecheck.productID
		WHERE		products.deleted = 1"	
);
$result = $mb->query($query);

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
	if($cnt == $max)
	{
		break;
	}
	
	$queryUpdate = sprintf(
		"	UPDATE		products_pricecheck
			SET			products_pricecheck.price = '0.00'
			WHERE		products_pricecheck.productWebsiteID = %d",
		$row['productWebsiteID']
	);
	$mb->query($queryUpdate);
	
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
	
	if($_debug == true)
	{
		echo "Ripping website " . $row['website'] . "<br/>";
	}
	
	
	$value = 0;
	
	if(isset($had[$row['website']]))
	{
		$value = $had[$row['website']];
		
		if($_debug == true)
		{
			echo "Already had this website.<br/>";
		}
		
		$query = sprintf(
			"	UPDATE		products_pricecheck
				SET			products_pricecheck.price = '%.2f',
							products_pricecheck.date_update = NOW(),
							products_pricecheck.errors = 0
				WHERE		products_pricecheck.website = '%s'",
			$had[$row['website']],
			$row['website']
		);
		$mb->query($query);
		
		if($_debug == true)
		{
			echo "Query: " . $query . "<br/>";
		}
	}
	else
	{
		/*
		**	Strip de website en haal de prijs eruit.
		**	De prijs komt uit een serie van velden waar die
		**	vermoedelijk in staat.
		*/
		
		$value = scraping($row['website']);
		
		if($value != 0)
		{
			$had[$row['website']] = number_format(($value > 0 ? $value : 0), 2, ".", "");
			
			if($_debug == true)
			{
				echo "Price found: " . $had[$row['website']] . "<br/>";
			}
			
			$query = sprintf(
				"	UPDATE		products_pricecheck
					SET			products_pricecheck.price = '%.2f',
								products_pricecheck.date_update = NOW(),
								products_pricecheck.errors = 0
					WHERE		products_pricecheck.website = '%s'",
				$had[$row['website']],
				$row['website']
			);
			$mb->query($query);
			
			if($_debug == true)
			{
				echo "Query: " . $query . "<br/>";
			}
		}
		else
		{
			$errono = ($row['errors']+1);
			
			$query = sprintf(
				"	UPDATE		products_pricecheck
					SET			products_pricecheck.price = '0.00',
								products_pricecheck.date_update = NOW(),
								products_pricecheck.errors = %d
					WHERE		products_pricecheck.website = '%s'",
				($had[$row['website']] == 0 ? $errono : 0),
				$row['website']
			);
			$mb->query($query);
			
			if($_debug == true)
			{
				echo "No price found.<br/>";
			}
		}
		
		$timer1 = time();
		
		sleep(1);
		
		$timer2 = time();
		
		if($_debug == true)
		{
			echo "Slept for " . ($timer2 - $timer1) . " seconds.<br/>";
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
		
		if($_debug == true)
		{
			echo "Added this value to the array.<br/>";
		}
	}
	
	if($_debug == true)
	{
		echo "Current script time is " . (time() - $start) . " seconds.<br/>";
		
		echo "<br/><br/>";
	}
	
	$cnt++;
}

if($_debug == true)
{
	echo "<br/><br/><br/><br/>";
}

/*
**	Stel een e-mail op om de admin's te laten
**	weten dat er een pricecheck is geweest.
*/

$html_table = "";

foreach($products AS $productID => $values)
{
	if($_debug == true)
	{
		echo "Starting with productID " . $productID . "<br/>";
	}
	
	/*
	**	Collect the current product data.
	*/
	
	$query3 = sprintf(
		"	SELECT		products.article_code,
						products.name,
						products.price,
						products.price_purchase,
						products.price_adviced
						taxes.percentage
			FROM		products
			INNER JOIN	taxes ON taxes.taxesID = products.taxesID
			WHERE		products.productID = %d",
		$productID
	);
	$result3 = $mb->query($query3);
	$row3 = $mb->fetch_assoc($result3);
	
	$row3['price_purchase'] += number_format(($row3['price_purchase']*($row3['percentage']/100)), 2, ".", "");
	
	$minimum_price = (number_format($row3['price_adviced'], 2, ".", "")/100)*10;
	$minimum_price = number_format($row3['price_purchase'], 2, ".", "") + $minimum_price;
	$minimum_price = number_format($minimum_price, 2, ".", "");
	
	if($_debug == true)
	{
		echo "The minimum price is " . $minimum_price . " (" . number_format($row3['price_purchase'], 2, ".", "") . ")<br/>";
	}
	
	
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
	
	if($_debug == true)
	{
		echo "Shipment price is " . $sRow['price'] . "<br/>";
	}
	
	
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
	
	if($_debug == true)
	{
		echo "The found prices are " . implode(", ", $prices) . "<br/>";
	}
	
	$lowest = 0;
	sort($prices);
	
	foreach($prices AS $price)
	{
		if($price == 0)
		{
			continue;
		}
		
		$lowest = $price;
		break;
	}
	
	if($_debug == true)
	{
		echo "The lowest price is " . $lowest . "<br/>";
	}
	
	
	/*
	**	Status bepalen van deze aanpassing
	**	OK: Geen bijzonderheden. Winstgevend verwerkt.
	**	W1: Waarschuwing. De concurrent gaat lager dan de minimale winst.
	**	W2: Waarschuwing. De concurrent gaat lager dan de inkoopsprijs.
	**	E1: Fout. Domeinnaam kent geen prijzen. Artikel overgeslagen.
	**	E2: Waarschuwing. Dit artikel heeft geen inkoopsprijs. Artikel overgeslagen.
	*/
	
	$status = "OK";
	$color = "green";
	$new_price = $lowest;
	$note = "Geen opmerkingen.";
	
	if($new_price == 0)
	{
		$status = "E1";
		$new_price = "0.00";
		$color = "red";
		$note = "Vergelijken is niet mogelijk.";
		
		if($_debug == true)
		{
			echo "Comparing is not possible because there is no price.<br/>";
		}
	}
	else if($row3['price_purchase'] == 0)
	{
		$status = "E2";
		$new_price = "0.00";
		$color = "red";
		$note = "Vergelijken is niet mogelijk.";
		
		if($_debug == true)
		{
			echo "Comparing is not possible because there is no purchase price set.<br/>";
		}
	}
	else if($lowest < $row3['price_purchase'])
	{
		$status = "W2";
		$new_price = $minimum_price;
		$color = "orange";
		$note = "Lager dan inkoop. Minimaal is &euro;" . $values[0]['profit'] . ".";
		
		if($_debug == true)
		{
			echo "The new price is lower then the purchase price.<br/>";
		}
	}
	else if($lowest < $minimum_price)
	{
		$status = "W1";
		$new_price = $minimum_price;
		$color = "orange";
		$note = "Minimale winst is &euro;" . $values[0]['profit'] . ".";
		
		if($_debug == true)
		{
			echo "The new price is lower then the minimum price.<br/>";
		}
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
	
	if(($new_price != $row3['price']) || $status != "OK")
	{
		if($_debug == true)
		{
			echo "New row added to the table.<br/>";
		}
		
		$html_table .= "<tr>
			<td class='" . $color . "'>" . $status . "</td>
			<td class='" . ($color == "red" ? "error" : "") . "'>" . sprintf("%05d", $row3['article_code']) . "</td>
			<td title='" . $row3['name'] . "' class='" . ($color == "red" ? "error" : "") . "'>" . substr($row3['name'], 0, 25) . " ...</td>
			<td class='" . ($color == "red" ? "error" : "") . "'>" . number_format($row3['price_purchase'], 2) . " euro</td>
			<td class='" . ($color == "red" ? "error" : "") . "'>" . number_format($lowest, 2) . " euro</td>
			<td class='" . ($color == "red" ? "error" : "") . "'>" . number_format($new_price, 2) . " euro</td>
		</tr>";
		
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
			
			if($_debug == true)
			{
				echo "Row added to the database.<br/><br/>";
			}
		}
	}
	else
	{
		if($_debug == true)
		{
			echo "Prices are the same, do not add the row.<br/><br/>";
		}
	}
}


$query = sprintf(
	"	DELETE FROM		products_pricecheck
		WHERE			products_pricecheck.errors > 6"
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
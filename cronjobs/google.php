<?php
define("_DEVELOPMENT_ENVIRONMENT", true);
define("_LANGUAGE_PACK", "nl");

require_once("/var/www/vhosts/justinharings.nl/merchant.justinharings.nl/library/php/classes/motherboard.php");	
$mb = new motherboard();

require_once("/var/www/vhosts/justinharings.nl/merchant.justinharings.nl/library/php/classes/database.php");
$db = new database();

	
	
function outputCSV($data) 
{
    $outputBuffer = fopen("php://output", 'w');
	
    foreach($data as $val) 
	{
        fputcsv($outputBuffer, $val);
    }
	
    fclose($outputBuffer);
}

function _transformCategoryURL($name)
{
	$name = strtolower($name);
	$name = strip_tags($name);
	
	$name = str_replace(" ", "_", $name);
	$name = str_replace("/", "_", $name);
	$name = str_replace("&", "en", $name);
	
	return $name;
}



$_merchants = array();

$query = sprintf(
	"	SELECT		merchant.merchantID
		FROM		merchant"
);
$result = $db->query($query);

while($row = $db->fetch_assoc($result))
{
	$_merchants[] = $row['merchantID'];
}



foreach($_merchants AS $merchantID)
{
	$_SESSION['merchantID'] = $merchantID;
	
	$articles = $mb->_runFunction("products", "view", array($merchantID, "export", "products.name", "0,9999999"));
	
	$num = 0;
	
	$_lang = $mb->_allLanguages();
	
	$langs = array();
	
	$langs[] = "nl";
	
	foreach($_lang AS $language)
	{
		$langs[] = strtolower($language['code']);
	}
	
	foreach($langs AS $language)
	{
		$return = array();
		
		foreach($articles AS $key => $value)
		{
			if($value['visibility'] < 2)
			{
				continue;
			}
			
			$details = $mb->_runFunction("products", "load", array($value['productID']));
			
			$dataShipment = $mb->_runFunction("shipment_methods", "load", array($details['shipmentID']));
			$exportFee = 0;
			
			if($language != "nl")
			{
				foreach($dataShipment['fees'] AS $value)
				{
					if($value['country'] == "United Kingdom" && $language == "en")
					{
						$exportFee = $value['fee'];
					}
					else if($value['country'] == "Germany" && $language == "de")
					{
						$exportFee = $value['fee'];
					}
				}
			}
			
			$return[$num]['id'] = $details['article_code'];
			$return[$num]['name'] = $details['name'];
			$return[$num]['description'] = nl2br($details['description']);
			$return[$num]['link'] = "https://www.haringstweewielers.com/" . $language . "/catalog/details/" . $details['productID'] . "/" . _transformCategoryURL($details[($language != "nl" ? strtoupper($language) . "_" : "") . 'name']) . ".html";
			$return[$num]['state'] = "nieuw";
			$return[$num]['price'] = number_format($details['price'], 2, ".", "") . " EUR";
			$return[$num]['stock'] = "Op voorraad";
			$return[$num]['image'] = "";
			$return[$num]['gtin'] = $details['barcode'];
			$return[$num]['mpn'] = $details['supplier_code'];
			$return[$num]['brand'] = $details['brand'];
			$return[$num]['shipping'] = ($details['shipment_costs'] + $exportFee) . " EUR";
			
			if($details['stock'] < 1 && $details['status'] < 3)
			{
				$return[$num]['stock'] = "Vooraf bestellen";
			}
			else if($details['stock'] < 1 && $details['status'] > 2)
			{
				$return[$num]['stock'] = "Niet op voorraad";
			}
			
			foreach($details['images'] AS $media)
			{
				if($media['thumb'])
				{
					$return[$num]['image'] = "https://merchant.justinharings.nl/library/media/products/" . $media['productMediaID'] . ".png";
				}	
			}
				
			$num++;
		}
		
		$data = array();
		$data[] = array("id", "titel", "beschrijving", "link", "staat", "prijs", "beschikbaarheid", "afbeeldingslink", "gtin", "mpn", "merk", "adult", "verzending(prijs)");
		
		foreach($return AS $value)
		{
			if	(
					$value['gtin'] == "" || strlen($value['gtin']) < 2 || $value['brand'] == ""
				)
			{
				continue;
			}
		
			$value['description'] = trim(preg_replace('/\s+/', ' ', $value['description']));
			
			$data[] = array($value['id'], $value['name'], $value['description'], $value['link'], $value['state'], $value['price'], $value['stock'], $value['image'], $value['gtin'], $value['mpn'], $value['brand'], "nee", $value['shipping']);
		}
		
		$folder = "/var/www/vhosts/justinharings.nl/merchant.justinharings.nl/library/csv/";
		$file = "google_" . $language . "_" . $merchantID . ".csv";
		
		if(file_exists($folder.$file))
		{
			unlink($folder.$file);
		}
		
		if(!is_dir($folder))
		{
			mkdir($folder, 0777);
		}
		
		try
		{
			$fp = fopen($folder.$file, 'w');
			
			foreach($data as $fields) 
			{
				fputcsv($fp, $fields);
			}
			
			fclose($fp);
		} 
		catch (Exception $e) 
		{
			print $e->getMessage(); exit;
		}
	}
}
?>
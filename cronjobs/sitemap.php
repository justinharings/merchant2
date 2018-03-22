<?php
function _createCategoryURL($name)
{
	$name = strtolower($name);
	$name = strip_tags($name);
	
	$name = str_replace(" ", "_", $name);
	$name = str_replace("/", "_", $name);
	$name = str_replace("&", "en", $name);
	
	return $name;
}



$_take_languages = 		array("nl", "en", "de");
$_websites = 			array(1 => "https://www.haringstweewielers.com/");
$_save_location =		array(1 => "/var/www/vhosts/justinharings.nl/haringstweewielers.com/sitemap_%s.xml");

$_categories = 			array(
							1 => array(
								"bicycles" => 1,
								"accessories" => 4,
								"parts" => 44
							)
						);
						
						

$urls = array();

require_once("/var/www/vhosts/justinharings.nl/merchant.justinharings.nl/library/php/classes/database.php");
$db = new database();

foreach($_websites AS $merchantID => $webaddress)
{
	// Main URLs
	foreach($_take_languages AS $lang)
	{
		$urls[] = $webaddress . $lang . "/";
	}
	
	
	
	// Service pages
	$query = sprintf(
		"	SELECT		content.name,
						content.seo_url
			FROM		content
			WHERE		content.merchantID = %d",
		$merchantID
	);
	$result = $db->query($query);
	
	while($row = $db->fetch_assoc($result))
	{
		foreach($_take_languages AS $lang)
		{
			$urls[$lang][] = $webaddress . $lang . "/service/" . $row['seo_url'];
		}
	}
	
	
	
	// Categorie pages
	foreach($_categories[1] AS $category => $categoryID)
	{
		foreach($_take_languages AS $lang)
		{
			$urls[$lang][] = $webaddress . $lang . "/catalog/" . $category . ".html";
		}
		
		$query = sprintf(
			"	SELECT		categories.*
				FROM		categories
				WHERE		categories.parentID = %d",
			$categoryID
		);
		$result = $db->query($query);
		
		while($row = $db->fetch_assoc($result))
		{
			$query2 = sprintf(
				"	SELECT		categories.*
					FROM		categories
					WHERE		categories.parentID = %d",
				$row['categoryID']
			);
			$result2 = $db->query($query2);
			
			while($row2 = $db->fetch_assoc($result2))
			{
				foreach($_take_languages AS $lang)
				{
					$urls[$lang][] = $webaddress . $lang . "/catalog/" . $category . "/" . $row2['categoryID'] . "/filters/none/" . _createCategoryURL($row2['name']) . ".html";
				}
				
				$query3 = sprintf(
					"	SELECT		products_cache.*
						FROM		products_cache
						WHERE		products_cache.merchantID = %d
							AND		products_cache.categoryID = %d
						GROUP BY	products_cache.productID
						ORDER BY	products_cache.name_sort",
					$merchantID,
					$row2['categoryID']
				);
				$result3 = $db->query($query3);
				
				while($row3 = $db->fetch_assoc($result3))
				{
					$row3['name'] = unserialize($row3['name']);
					
					foreach($_take_languages AS $lang)
					{
						if($row3['name'][$lang] != "")
						{
							$urls[$lang][] = $webaddress . $lang . "/catalog/" . $category . "/" . _createCategoryURL($row2['name']) . "/details/" . $row3['productID'] . "/" . _createCategoryURL($row3['name'][$lang]) . ".html";
						}
					}
				}
			}
		}
	}
	
	foreach($_take_languages AS $lang)
	{
		$xml = '<?xml version="1.0" encoding="UTF-8"?>
				<urlset
				      xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
				      xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
				      xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
				            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';
				            
		foreach($urls[$lang] AS $url)
		{
			$xml .= '
				<url>
				  <loc>' . $url . '</loc>
				  <priority>1.00</priority>
				</url>
			';
		}
		
		$xml .= '</urlset>';
	
	
		try 
		{
			
			$sLocation = sprintf(
					$_save_location[$merchantID],
				$lang
			);
			
			$file = fopen($sLocation, "w");
			fwrite($file, $xml);
			fclose($file);
		}
		catch (Exception $e) 
		{
			echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
	}
	
	$urls = array();
}
?>
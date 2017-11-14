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

while($row = $mb->fetch_assoc($result))
{
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
			
			$had[$row['website']] = (floatVal($value) > 0 ? $value : 0);
			
			//print $row['website'] . "<br/>" . $value . "<br/><br/>";
		}
	}
	
	if($value > 0)
	{
		$query = sprintf(
			"	UPDATE		products_pricecheck
				SET			products_pricecheck.price = '%.2f',
							products_pricecheck.date_update = NOW()
				WHERE		products_pricecheck.productWebsiteID = %d",
			$value,
			$row['productWebsiteID']
		);
		$mb->query($query);
	}
}

print "Done.";
?>
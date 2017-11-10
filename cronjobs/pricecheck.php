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
			$explode = explode($row['field'], $content);
			$explode = explode('"', $explode[1]);
			
			$value = $explode[0];
			$value = str_replace(",", ".", $value);
			
			$had[$row['website']] = $value;
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
?>
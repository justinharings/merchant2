<?php
ini_set('display_startup_errors', true);
error_reporting(E_ALL);
ini_set('display_errors', true);
ini_set("error_log", "php-error.log");

define("_DEVELOPMENT_ENVIRONMENT", true);
define("_MERCHANT_ID", 1);
define("_LANGUAGE_PACK", "nl");

if(!isset($_SESSION))
{
	session_start();
}

$_SESSION['merchantID'] = 1;

require_once("/var/www/vhosts/justinharings.nl/merchant.justinharings.nl/library/php/classes/database.php");
$db = new database();

require_once("/var/www/vhosts/justinharings.nl/merchant.justinharings.nl/library/php/classes/motherboard.php");
$mb = new motherboard();


function getNewArticleCode()
{
	require_once("/var/www/vhosts/justinharings.nl/merchant.justinharings.nl/library/php/classes/database.php");
	$db = new database();

	$query = sprintf(
		"	SELECT		MAX(CONVERT(products.article_code, UNSIGNED INTEGER)) AS article_code
			FROM		products
			WHERE		products.merchantID = 1"
	);
	$result = $db->query($query);
	$row = $db->fetch_assoc($result);

	return ($row['article_code']+1);
}

function roundUpTo5Cents($value)
{
	$valueInString = strval(round($value,2));

	if(strpos($valueInString, ".") == 0) $valueInString = $valueInString.".00";

	$valueArray = explode(".", $valueInString);
	$substringValue = substr($valueArray[1], 1);

	if($substringValue >= 1 && $substringValue <= 5)
	{
		$tempValue = str_replace(substr($valueArray[1], 1), 5, substr($valueArray[1], 1));
		$tempValue = substr($valueArray[1],0,1).$tempValue;
		$newvalue = floatval($valueArray[0].".".$tempValue);
	}
	elseif($substringValue == 0)
	{
		$newvalue = floatval($value);
	}else
	{
		$newFloat = floatval($valueArray[0].".".substr($valueArray[1],0,1));
		$newvalue = ($newFloat+0.1);
	}

	return $newvalue;
}

function translateColor($color, $lang)
{
	switch($color)
	{
		case "Chroom":
			if($lang == "DE")
			{
				return "Chrom";
			}
			else if($lang == "EN")
			{
				return "Chrome";
			}
		break;

		case "Koper":
			if($lang == "DE")
			{
				return "Kupfer";
			}
			else if($lang == "EN")
			{
				return "Copper";
			}
		break;

		case "Oranje":
			if($lang == "DE")
			{
				return "Orange";
			}
			else if($lang == "EN")
			{
				return "Orange";
			}
		break;

		case "Groen":
			if($lang == "DE")
			{
				return "Grün";
			}
			else if($lang == "EN")
			{
				return "Green";
			}
		break;

		case "Blauw":
			if($lang == "DE")
			{
				return "Blau";
			}
			else if($lang == "EN")
			{
				return "Blue";
			}
		break;

		case "Zwart":
			if($lang == "DE")
			{
				return "Schwarz";
			}
			else if($lang == "EN")
			{
				return "Black";
			}
		break;

		case "Zilver/Zwart":
			if($lang == "DE")
			{
				return "Silber/Schwarz";
			}
			else if($lang == "EN")
			{
				return "Silver/Black";
			}
		break;

		case "Zilver":
			if($lang == "DE")
			{
				return "Silber";
			}
			else if($lang == "EN")
			{
				return "Silver";
			}
		break;

		case "Zwart/Grijs":
			if($lang == "DE")
			{
				return "Schwarz/Grau";
			}
			else if($lang == "EN")
			{
				return "Black/Gray";
			}
		break;

		case "Wit":
			if($lang == "DE")
			{
				return "Weiß";
			}
			else if($lang == "EN")
			{
				return "White";
			}
		break;
		
		case "Rood":
			if($lang == "DE")
			{
				return "Rot";
			}
			else if($lang == "EN")
			{
				return "Red";
			}
		break;
		
		case "Paars":
			if($lang == "DE")
			{
				return "Lila";
			}
			else if($lang == "EN")
			{
				return "Purple";
			}
		break;
		
		case "Roze":
			if($lang == "DE")
			{
				return "Rosa";
			}
			else if($lang == "EN")
			{
				return "Pink";
			}
		break;
		
		case "Grijs":
			if($lang == "DE")
			{
				return "Grau";
			}
			else if($lang == "EN")
			{
				return "Gray";
			}
		break;
		
		case "Grijs":
			if($lang == "DE")
			{
				return "Grau";
			}
			else if($lang == "EN")
			{
				return "Gray";
			}
		break;
		
		case "Goud":
			if($lang == "DE")
			{
				return "Gold";
			}
			else if($lang == "EN")
			{
				return "Gold";
			}
		break;
		
		case "Geel":
			if($lang == "DE")
			{
				return "Gelb";
			}
			else if($lang == "EN")
			{
				return "Yellow";
			}
		break;
		
		case "Bruin":
			if($lang == "DE")
			{
				return "Braun";
			}
			else if($lang == "EN")
			{
				return "Brown";
			}
		break;
	}
}

function createThumbnail($newWidth, $newHeight, $path)
{	
    $mime = getimagesize($path);

    if($mime['mime']=='image/png'){ $src_img = imagecreatefrompng($path); }
    if($mime['mime']=='image/jpg'){ $src_img = imagecreatefromjpeg($path); }
    if($mime['mime']=='image/jpeg'){ $src_img = imagecreatefromjpeg($path); }
    if($mime['mime']=='image/pjpeg'){ $src_img = imagecreatefromjpeg($path); }

    $old_x = imageSX($src_img);
    $old_y = imageSY($src_img);

    if($old_x > $old_y)
    {
        $thumb_w    =   $newWidth;
        $thumb_h    =   $old_y/$old_x*$newWidth;
    }

    if($old_x < $old_y)
    {
        $thumb_w    =   $old_x/$old_y*$newHeight;
        $thumb_h    =   $newHeight;
    }

    if($old_x == $old_y)
    {
        $thumb_w    =   $newWidth;
        $thumb_h    =   $newHeight;
    }

    $dst_img        =   ImageCreateTrueColor($thumb_w,$thumb_h);

    imagecopyresampled($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y);


    // New save location
    if($mime['mime']=='image/png'){ $result = imagepng($dst_img,$path,8); }
    if($mime['mime']=='image/jpg'){ $result = imagejpeg($dst_img,$path,80); }
    if($mime['mime']=='image/jpeg'){ $result = imagejpeg($dst_img,$path,80); }
    if($mime['mime']=='image/pjpeg'){ $result = imagejpeg($dst_img,$path,80); }

    imagedestroy($dst_img);
    imagedestroy($src_img);
    
    return $result;
}


if(isset($_GET['image']))
{
	$query = sprintf(
		"	SELECT		products.productID
			FROM		products
			WHERE		products.barcode = '%s'
				AND		products.deleted = 0",
		$_GET['barcode']
	);
	$result = $db->query($query);
	$row = $db->fetch_assoc($result);
	
	$data = $mb->_runFunction("products", "load", array($row['productID']));
	
	foreach($data['categories'] AS $key => $value)
	{
		$filter_values = $data['categories'][$key]['filters']['filters'];
		
		if(is_array($filter_values) && count($filter_values) == 0)
		{
			continue;
		}
		
		foreach($filter_values AS $key => $filter)
		{
			$query = sprintf(
				"	SELECT		products_properties.value
					FROM		products_properties
					WHERE		products_properties.productID = %d
						AND		products_properties.key = '%s'",
				$row['productID'],
				$filter['name']
			);
			$result = $db->query($query);
			$row2 = $db->fetch_assoc($result);
			
			$langauges = array("NL", "EN", "DE");
			
			if($row2['value'] != "")
			{
				foreach($langauges AS $lang)
				{
					$query = sprintf(
						"	INSERT INTO		products_filters
							SET				products_filters.productID = %d,
											products_filters.filterID = %d,
											products_filters.language = '%s',
											products_filters.value = '%s'",
						$row['productID'],
						$filter['filterID'],
						$lang,
						(strtolower($filter['name']) == "kleur" && $lang != "NL" ? translateColor($row2['value'], $lang) : $row2['value'])
					);
					$db->query($query);
				}
			}
		}
	}
	
	$query = sprintf(
		"	INSERT INTO		products_media
			SET				products_media.productID = %d,
							products_media.type = 'image',
							products_media.youtube_url = '',
							products_media.thumb = 1",
		$row['productID']
	);
	$result = $db->query($query);
	$insert_id = $db->insert_id($result);
	
	$file = $_SERVER['DOCUMENT_ROOT'] . '/library/media/products/' . $insert_id . ".png";
	
	copy($_GET['image'], $file);
	
	// First create smaller image.
	createThumbnail(600, 600, $file);
	
	//get original image attributes
	list($width, $height, $type, $attr) = getimagesize($file);
	print $width;
	print $height;
	$thumb = imagecreatefromjpeg($file);
	$thumb_p = imagecreatetruecolor(900, 900);
	
	imagecopyresampled($thumb_p, $thumb, 0, 0, 0, 0, 900, 900, $width, $height);
	
	//save the file
	imagejpeg($thumb_p, $file, 100);
	
	//header("location: /juncker.php?kernwoord=" . $_GET['kernwoord']);
	exit;
}
?>
<html>
	<head>
		<title>Juncker Magic</title>

		<style>
			div.block
			{
				width: calc(25% - 22px);

				margin: 10px;
				padding: 20px 0px;
				float: left;

				text-align: center;

				border: 1px solid #ccc;
			}

			div.form
			{
				width: calc(100% - 22px);

				margin: 10px;
				padding: 20px 0px;
				float: left;

				text-align: center;

				border: 1px solid #ccc;
			}
		</style>
	</head>

	<body>
		<?php
		$ftp_server = "ftp.accell-group.com";
		$ftp_user = "e-dst-harings";
		$ftp_pass = "16d165bR";

		$file = "/ArticlesandStock/Juncker_DST_V2.csv";

		$filename = "ftp://" . $ftp_user . ":" . $ftp_pass . "@" . $ftp_server . $file;
		$file = fopen($filename, "r");

		$content = file_get_contents($filename);
		$rows = str_getcsv($content, "\n");

		$products = array();
		$kernwoorden = array();
		$num = 0;

		$query = sprintf(
			"	SELECT		products.barcode
				FROM		products
				WHERE		products.externalStockID = 2
					AND		products.deleted = 0"
		);
		$result = $db->query($query);

		$barcodes = array();

		while($row = $db->fetch_assoc($result))
		{
			$barcodes[] = $row['barcode'];
		}

		foreach($rows AS $row)
		{
			$column = explode(",", $row);

			if(!in_array($column[2], $barcodes))
			{
				if($column[6] == "VERVALLEN")
				{
					continue;
				}

				if(isset($_GET['kernwoord']) && $column[11] != $_GET['kernwoord'] && $num != 0)
				{
					continue;
				}
				else if (!isset($_GET['kernwoord']))
				{
					if(!in_array($column[11], $kernwoorden) && $num != 0)
					{
						$kernwoorden[] = $column[11];
						continue;
					}
				}

				if($column[17] == "")
				{
					continue;
				}

				if(strpos($column[18], "Assorti") !== false)
				{
					continue;
				}

				if(strpos($column[28], " DS ") !== false)
				{
					continue;
				}
				
				if(strpos($column[14], "MERKLOOS") !== false)
				{
					$column[14] = str_replace("MERKLOOS", "", $column[14]);
				}

				$col = 0;

				foreach($column AS $value)
				{
					$products[$num][$col] = $value;

					$col++;
				}

				$num++;
			}
		}

		if(count($kernwoorden) > 0)
		{
			foreach($kernwoorden AS $kernwoord)
			{
				$file = "juncker/" . $kernwoord . ".txt";
				$date = file_get_contents($file);

				$date1 = new DateTime($date);
				$date2 = new DateTime(date("Y-m-d"));

				$diff = $date1->diff($date2);
				?>

				<div onclick="document.location.href='?kernwoord=<?= $kernwoord ?>'" class="block" style="background-color: <?= $diff->days >= 14 ? "#f1f1f1;" : "#ffffff" ?>">
					<?= $kernwoord ?><br/>
					<small><?= $diff->days ?> dagen geleden</small>
				</div>

				<?php
			}
		}
		else
		{
			$length = count($products);

			$file = "juncker/" . $_GET['kernwoord'] . ".txt";
			$date = file_put_contents($file, date("Y-m-d"));

			for($i = 1; $i <= $length; $i++)
			{
				$products[$i][19] = ucfirst(strtolower($products[$i][19]));

				if(strlen($products[$i][14]) > 3)
				{
					$products[$i][14] = ucfirst(strtolower($products[$i][14])); 
				}
				
				switch($_GET['kernwoord'])
				{
					case "BEL":
						$description = "Bellen en toeters worden per stuk verzonden en eventueel samengevoegd met een groter pakket. Bellen met een bepaalde opdruk uit een serie of tekenfilm kunnen een afwijkende opdruk hebben ten opzichte van de afbeelding. Vanzelfsprekend wel uit dezelfde serie of tekenfilm.<br/><br/>Deze bel voor 17:00 uur besteld is binnen twee werkdagen in huis!";
						$shipmentID = 6;
						$categories_1 = 36;
						$categories_2 = 39;

						$filter = array(
							// Nederlands

							0 => array(
								"filter_language" => "nl",
								"filter_key" => "Besteleenheid",
								"filter_value" => $products[$i][4] . " stuk(s)"
							),

							1 => array(
								"filter_language" => "nl",
								"filter_key" => "Kleur",
								"filter_value" => $products[$i][19]
							),
							
							2 => array(
								"filter_language" => "nl",
								"filter_key" => "Type",
								"filter_value" => "Bel"
							),


							// Engels

							3 => array(
								"filter_language" => "EN",
								"filter_key" => "Order unity",
								"filter_value" => $products[$i][4] . " piece(s)"
							),

							4 => array(
								"filter_language" => "EN",
								"filter_key" => "Color",
								"filter_value" => translateColor($products[$i][19], "EN")
							),
							
							5 => array(
								"filter_language" => "EN",
								"filter_key" => "Type",
								"filter_value" => "Bell"
							),


							// Duits

							6 => array(
								"filter_language" => "DE",
								"filter_key" => "Bestelleinheit",
								"filter_value" => $products[$i][4] . " Stück(e)"
							),

							7 => array(
								"filter_language" => "DE",
								"filter_key" => "Farbe",
								"filter_value" => translateColor($products[$i][19], "DE")
							),
							
							8 => array(
								"filter_language" => "DE",
								"filter_key" => "Farbe",
								"filter_value" => "Klingel"
							)
						);
					break;
				}

				$brandID = 0;

				if($products[$i][14] != "")
				{
					$query = sprintf(
						"	SELECT		brands.brandID
							FROM		brands
							WHERE		brands.merchantID = 1
								AND		brands.name = '%s'",
						$products[$i][14]
					);
					$result = $db->query($query);
					$row = $db->fetch_assoc($result);

					if($row['brandID'] != "")
					{
						$brandID = $row['brandID'];
					}
					else
					{
						$query = sprintf(
							"	INSERT INTO		brands
								SET				brands.merchantID = 1,
												brands.name = '%s'",
							$products[$i][14]
						);
						$result = $db->query($query);
						$brandID = $db->insert_id($result);
					}
				}
				?>

				<div class="form">
					<img src="<?= $products[$i][17] ?>" style="width: 200px;" /><br/><br/>
					<?= ucwords(strtolower($products[$i][28])); ?><br/>
					<strong>Kleur:</strong> <?= $products[$i][19] ?>
					/ <?= (translateColor($products[$i][19], "EN") != "" ? translateColor($products[$i][19], "EN") : "<strong style='color: red'>Onbekend</strong>") ?>
					/ <?= (translateColor($products[$i][19], "DE") != "" ? translateColor($products[$i][19], "DE") : "<strong style='color: red'>Onbekend</strong>") ?><br/>
					<strong>EAN:</strong> <?= $products[$i][2] ?><Br/>
					<br/>
					<form method="post" id="form" action="/library/php/posts/catalogus/products.php" enctype="multipart/form-data">
						<input type="hidden" name="productID" id="productID" value="0" />
						<input type="hidden" name="returnURL" id="returnURL" value="/juncker.php?kernwoord=<?= $_GET['kernwoord'] ?>&image=<?= $products[$i][17] ?>&barcode=<?= $products[$i][2] ?>" />

						<input type="hidden" name="article_code" id="article_code" value="<?= getNewArticleCode() ?>" />
						<input type="hidden" name="supplier_code" id="supplier_code" value="<?= $products[$i][1] ?>" />
						<input type="hidden" name="barcode" id="barcode" value="<?= $products[$i][2] ?>" />
						<input type="hidden" name="shipmentID" id="shipmentID" value="<?= $shipmentID ?>" />
						<input type="hidden" name="brandID" id="brandID" value="<?= $brandID ?>" />
						<input type="hidden" name="groupID" id="groupID	" value="2" />
						<input type="hidden" name="visibility" id="visibility" value="3" />
						<input type="hidden" name="bookmark" id="bookmark" value="0" />
						<input type="hidden" name="workorders_products" id="workorders_products" value="0" />
						<input type="hidden" name="workorders_manhours" id="workorders_manhours" value="0" />
						<input type="hidden" name="price_adviced" id="price_adviced" value="<?= $products[$i][26] ?>" />
						<input type="hidden" name="price_purchase" id="price_purchase" value="<?= $products[$i][24] ?>" />
						<input type="hidden" name="taxesID" id="taxesID" value="1" />
						<input type="hidden" name="categories[]" id="categories_0" value="4" />
						<input type="hidden" name="categories[]" id="categories_1" value="<?= $categories_1 ?>" />
						<input type="hidden" name="categories[]" id="categories_2" value="<?= $categories_2 ?>" />

						<?php
						$num = 0;

						foreach($filter AS $key => $value)
						{
							?>
							
							<input type="hidden" name="filter_language[]" id="filter_language_<?= $num ?>" value="<?= $value['filter_language'] ?>" />
							<input type="hidden" name="filter_key[]" id="filter_key_<?= $num ?>" value="<?= $value['filter_key'] ?>" />
							<input type="hidden" name="filter_value[]" id="filter_value_<?= $num ?>" value="<?= $value['filter_value'] ?>" />
							
							<?php
							$num++;
						}
						?>

						<input type="hidden" name="status" id="status" value="1" />
						<input type="hidden" name="stock_type" id="stock_type" value="6" />
						<input type="hidden" name="externalStockID" id="externalStockID" value="2" />
						<input type="hidden" name="delivery_days" id="delivery_days" value="2" />
						<input type="hidden" name="description" id="description" value="<?= $description ?>" />
						<input type="hidden" name="weight" id="weight" value="<?= $products[$i][22] ?>" />
						<input type="hidden" name="price" id="price" value="<?= (($products[$i][24] * 1.65) < $products[$i][26]) ? roundUpTo5Cents($products[$i][24] * 1.65) : $products[$i][26] ?>" />

						<strong>NL Naam:</strong><br/>
						<input type="text" name="name" id="name" value="<?= $products[$i][14] ?> Bel <?= $products[$i][19] ?>" style="width: 500px; text-align: center;" /><br/>
						<br/>
						<strong>EN Naam:</strong><br/>
						<input type="text" name="EN_name" id="EN_name" value="<?= $products[$i][14] ?> Bell <?= translateColor($products[$i][19], "EN") ?>" style="width: 500px; text-align: center;" /><br/>
						<br/>
						<strong>DE Naam:</strong><br/>
						<input type="text" name="DE_name" id="DE_name" value="<?= $products[$i][14] ?> Klingel <?= translateColor($products[$i][19], "DE") ?>" style="width: 500px; text-align: center;" /><br/>
						<br/>
						<input type="submit" name="submit" id="submit" value="Toevoegen" style="width: 500px;" />
					</form>
				</div>
				<?php
			}
		}
		?>
	</body>
</html>
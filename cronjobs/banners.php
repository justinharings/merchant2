<?php
require_once("/var/www/vhosts/justinharings.nl/merchant.justinharings.nl/library/php/classes/database.php");
$db = new database();

$_SERVER['DOCUMENT_ROOT'] = "/var/www/vhosts/justinharings.nl/merchant.justinharings.nl";


// Homepage banner

$files = array();

if($handle = opendir(__DIR__ . '/banners/nl/homepage/')) 
{
	while (false !== ($entry = readdir($handle))) 
	{
		if($entry == "." || $entry == "..")
		{
			continue;
		}
		
		$files[] = $entry;
	}
	
	closedir($handle);
}

$oldfile = $_SERVER['DOCUMENT_ROOT'] . '/library/media/banners/102.jpg';

$image = array_rand($files);
$image = $files[$image];

$file = __DIR__ . '/banners/nl/homepage/' . $image;

if(file_get_contents($oldfile) == file_get_contents($file))
{
	$image = array_rand($files);
	$image = $files[$image];
	
	$file = __DIR__ . '/banners/nl/homepage/' . $image;
}

$url = str_replace(".jpg", "", $image);
$url = str_replace(".png", "", $url);
$url = str_replace("|", "/", $url);

$query = sprintf(
	"	UPDATE			banners
		SET				banners.url = '%s'
		WHERE			banners.bannerID = 102",
	$url
);
$db->query($query);

$content = file_get_contents($file);
file_put_contents($oldfile, $content);


// Quick links

$files = array();

if($handle = opendir(__DIR__ . '/banners/nl/quick_links/')) 
{
	while (false !== ($entry = readdir($handle))) 
	{
		if($entry == "." || $entry == "..")
		{
			continue;
		}
		
		$files[] = $entry;
	}
	
	closedir($handle);
}

$rand = array_rand($files);
$image1 = $files[$rand];

unset($files[$rand]);

$rand = array_rand($files);
$image2 = $files[$rand];

unset($files[$rand]);

$rand = array_rand($files);
$image3 = $files[$rand];

unset($files[$rand]);

$rand = array_rand($files);
$image4 = $files[$rand];

unset($files[$rand]);

$url = str_replace(".jpg", "", $image1);
$url = str_replace(".png", "", $url);
$url = str_replace("|", "/", $url);

$query = sprintf(
	"	UPDATE			banners
		SET				banners.url = '%s'
		WHERE			banners.bannerID = 4",
	$url
);
$db->query($query);

$file = __DIR__ . '/banners/nl/quick_links/' . $image1;
$oldfile = $_SERVER['DOCUMENT_ROOT'] . '/library/media/banners/4.jpg';

$content = file_get_contents($file);
file_put_contents($oldfile, $content);

/* ** */
$url = str_replace(".jpg", "", $image2);
$url = str_replace(".png", "", $url);
$url = str_replace("|", "/", $url);

$query = sprintf(
	"	UPDATE			banners
		SET				banners.url = '%s'
		WHERE			banners.bannerID = 5",
	$url
);
$db->query($query);

$file = __DIR__ . '/banners/nl/quick_links/' . $image2;
$oldfile = $_SERVER['DOCUMENT_ROOT'] . '/library/media/banners/5.jpg';

$content = file_get_contents($file);
file_put_contents($oldfile, $content);

/* ** */
$url = str_replace(".jpg", "", $image3);
$url = str_replace(".png", "", $url);
$url = str_replace("|", "/", $url);

$query = sprintf(
	"	UPDATE			banners
		SET				banners.url = '%s'
		WHERE			banners.bannerID = 6",
	$url
);
$db->query($query);

$file = __DIR__ . '/banners/nl/quick_links/' . $image3;
$oldfile = $_SERVER['DOCUMENT_ROOT'] . '/library/media/banners/6.jpg';

$content = file_get_contents($file);
file_put_contents($oldfile, $content);

/* ** */
$url = str_replace(".jpg", "", $image4);
$url = str_replace(".png", "", $url);
$url = str_replace("|", "/", $url);

$query = sprintf(
	"	UPDATE			banners
		SET				banners.url = '%s'
		WHERE			banners.bannerID = 7",
	$url
);
$db->query($query);

$file = __DIR__ . '/banners/nl/quick_links/' . $image4;
$oldfile = $_SERVER['DOCUMENT_ROOT'] . '/library/media/banners/7.jpg';

$content = file_get_contents($file);
file_put_contents($oldfile, $content);


// Fietsen 1 banner

$files = array();

if($handle = opendir(__DIR__ . '/banners/nl/fietsen_1/')) 
{
	while (false !== ($entry = readdir($handle))) 
	{
		if($entry == "." || $entry == "..")
		{
			continue;
		}
		
		$files[] = $entry;
	}
	
	closedir($handle);
}

$oldfile = $_SERVER['DOCUMENT_ROOT'] . '/library/media/banners/41.jpg';

$image = array_rand($files);
$image = $files[$image];

$file = __DIR__ . '/banners/nl/fietsen_1/' . $image;

if(file_get_contents($oldfile) == file_get_contents($file))
{
	$image = array_rand($files);
	$image = $files[$image];
	
	$file = __DIR__ . '/banners/nl/fietsen_1/' . $image;
}

$url = str_replace(".jpg", "", $image);
$url = str_replace(".png", "", $url);
$url = str_replace("|", "/", $url);

$query = sprintf(
	"	UPDATE			banners
		SET				banners.url = '%s'
		WHERE			banners.bannerID = 41",
	$url
);
$db->query($query);

$content = file_get_contents($file);
file_put_contents($oldfile, $content);


// Fietsen 2 en 3 banner

$files = array();

if($handle = opendir(__DIR__ . '/banners/nl/fietsen_2/')) 
{
	while (false !== ($entry = readdir($handle))) 
	{
		if($entry == "." || $entry == "..")
		{
			continue;
		}
		
		$files[] = $entry;
	}
	
	closedir($handle);
}

$rand = array_rand($files);
$image1 = $files[$rand];

unset($files[$rand]);

$rand = array_rand($files);
$image2 = $files[$rand];

unset($files[$rand]);

$url = str_replace(".jpg", "", $image1);
$url = str_replace(".png", "", $url);
$url = str_replace("|", "/", $url);

$query = sprintf(
	"	UPDATE			banners
		SET				banners.url = '%s'
		WHERE			banners.bannerID = 42",
	$url
);
$db->query($query);

$file = __DIR__ . '/banners/nl/fietsen_2/' . $image1;
$oldfile = $_SERVER['DOCUMENT_ROOT'] . '/library/media/banners/42.jpg';

$content = file_get_contents($file);
file_put_contents($oldfile, $content);

/* ** */
$url = str_replace(".jpg", "", $image2);
$url = str_replace(".png", "", $url);
$url = str_replace("|", "/", $url);

$query = sprintf(
	"	UPDATE			banners
		SET				banners.url = '%s'
		WHERE			banners.bannerID = 43",
	$url
);
$db->query($query);

$file = __DIR__ . '/banners/nl/fietsen_2/' . $image2;
$oldfile = $_SERVER['DOCUMENT_ROOT'] . '/library/media/banners/43.jpg';

$content = file_get_contents($file);
file_put_contents($oldfile, $content);

?>
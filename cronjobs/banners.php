<?php
require_once("/var/www/vhosts/justinharings.nl/merchant.justinharings.nl/library/php/classes/database.php");
$db = new database();

$_SERVER['DOCUMENT_ROOT'] = "/var/www/vhosts/justinharings.nl/merchant.justinharings.nl";


// Homepage banner

$files = array();

if($handle = opendir(__DIR__ . '/banners/homepage/')) 
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

// NL, EN, DE
$oldfiles = array(102, 87, 103);

foreach($oldfiles AS $oldFileID)
{
	$oldfile = $_SERVER['DOCUMENT_ROOT'] . '/library/media/banners/' . $oldFileID . '.jpg';
	
	if($oldFileID == 102)
	{
		$image = array_rand($files);
		$image = $files[$image];
		
		$file = __DIR__ . '/banners/homepage/' . $image;
		
		if(file_get_contents($oldfile) == file_get_contents($file))
		{
			$image = array_rand($files);
			$image = $files[$image];
			
			$file = __DIR__ . '/banners/homepage/' . $image;
		}
		
		$url = str_replace(".jpg", "", $image);
		$url = str_replace(".png", "", $url);
		$url = str_replace("|", "/", $url);
	}
	
	$query = sprintf(
		"	UPDATE			banners
			SET				banners.url = '%s'
			WHERE			banners.bannerID = %d",
		$url,
		$oldFileID
	);
	$db->query($query);
	
	$content = file_get_contents($file);
	file_put_contents($oldfile, $content);
}




/* *********************************************************************************************** */




// Quick links

$files = array();

if($handle = opendir(__DIR__ . '/banners/quick_links/')) 
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


// Quick link 1
// NL, EN, DE
$oldfiles = array(4, 88, 104);

foreach($oldfiles AS $oldFileID)
{
	$url = str_replace(".jpg", "", $image1);
	$url = str_replace(".png", "", $url);
	$url = str_replace("|", "/", $url);
	
	$query = sprintf(
		"	UPDATE			banners
			SET				banners.url = '%s'
			WHERE			banners.bannerID = %d",
		$url,
		$oldFileID
	);
	$db->query($query);
	
	$file = __DIR__ . '/banners/quick_links/' . $image1;
	$oldfile = $_SERVER['DOCUMENT_ROOT'] . '/library/media/banners/' . $oldFileID . '.jpg';
	
	$content = file_get_contents($file);
	file_put_contents($oldfile, $content);
}


// Quick link 2
// NL, EN, DE
$oldfiles = array(5, 90, 106);

foreach($oldfiles AS $oldFileID)
{
	$url = str_replace(".jpg", "", $image2);
	$url = str_replace(".png", "", $url);
	$url = str_replace("|", "/", $url);
	
	$query = sprintf(
		"	UPDATE			banners
			SET				banners.url = '%s'
			WHERE			banners.bannerID = %d",
		$url,
		$oldFileID
	);
	$db->query($query);
	
	$file = __DIR__ . '/banners/quick_links/' . $image2;
	$oldfile = $_SERVER['DOCUMENT_ROOT'] . '/library/media/banners/' . $oldFileID . '.jpg';
	
	$content = file_get_contents($file);
	file_put_contents($oldfile, $content);
}


// Quick link 3
// NL, EN, DE
$oldfiles = array(6, 89, 105);

foreach($oldfiles AS $oldFileID)
{
	$url = str_replace(".jpg", "", $image4);
	$url = str_replace(".png", "", $url);
	$url = str_replace("|", "/", $url);
	
	$query = sprintf(
		"	UPDATE			banners
			SET				banners.url = '%s'
			WHERE			banners.bannerID = %d",
		$url,
		$oldFileID
	);
	$db->query($query);
	
	$file = __DIR__ . '/banners/quick_links/' . $image4;
	$oldfile = $_SERVER['DOCUMENT_ROOT'] . '/library/media/banners/' . $oldFileID . '.jpg';
	
	$content = file_get_contents($file);
	file_put_contents($oldfile, $content);
}


// Quick link 4
// NL, EN, DE
$oldfiles = array(7, 91, 107);

foreach($oldfiles AS $oldFileID)
{
	$url = str_replace(".jpg", "", $image3);
	$url = str_replace(".png", "", $url);
	$url = str_replace("|", "/", $url);
	
	$query = sprintf(
		"	UPDATE			banners
			SET				banners.url = '%s'
			WHERE			banners.bannerID = %d",
		$url,
		$oldFileID
	);
	$db->query($query);
	
	$file = __DIR__ . '/banners/quick_links/' . $image3;
	$oldfile = $_SERVER['DOCUMENT_ROOT'] . '/library/media/banners/' . $oldFileID . '.jpg';
	
	$content = file_get_contents($file);
	file_put_contents($oldfile, $content);
}




/* *********************************************************************************************** */




// Fietsen groot

$files = array();

if($handle = opendir(__DIR__ . '/banners/fietsen_groot/')) 
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

// NL, EN, DE
$oldfiles = array(41, 93, 109);

foreach($oldfiles AS $oldFileID)
{
	$oldfile = $_SERVER['DOCUMENT_ROOT'] . '/library/media/banners/' . $oldFileID . '.jpg';
	
	if($oldFileID == 41)
	{
		$image = array_rand($files);
		$image = $files[$image];
		
		$file = __DIR__ . '/banners/fietsen_groot/' . $image;
		
		if(file_get_contents($oldfile) == file_get_contents($file))
		{
			$image = array_rand($files);
			$image = $files[$image];
			
			$file = __DIR__ . '/banners/fietsen_groot/' . $image;
		}
		
		$url = str_replace(".jpg", "", $image);
		$url = str_replace(".png", "", $url);
		$url = str_replace("|", "/", $url);
	}
	
	$query = sprintf(
		"	UPDATE			banners
			SET				banners.url = '%s'
			WHERE			banners.bannerID = %d",
		$url,
		$oldFileID
	);
	$db->query($query);
	
	$content = file_get_contents($file);
	file_put_contents($oldfile, $content);
}



/* *********************************************************************************************** */




// Fietsen middel

$files = array();

if($handle = opendir(__DIR__ . '/banners/fietsen_middel/')) 
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

// Middel 1
// NL, EN, DE
$oldfiles = array(42, 94, 110);

foreach($oldfiles AS $oldFileID)
{
	$query = sprintf(
		"	UPDATE			banners
			SET				banners.url = '%s'
			WHERE			banners.bannerID = %d",
		$url,
		$oldFileID
	);
	$db->query($query);
	
	$file = __DIR__ . '/banners/fietsen_middel/' . $image1;
	$oldfile = $_SERVER['DOCUMENT_ROOT'] . '/library/media/banners/' . $oldFileID . '.jpg';
	
	$content = file_get_contents($file);
	file_put_contents($oldfile, $content);
}

// Middel 2
// NL, EN, DE
$oldfiles = array(43, 95, 111);

foreach($oldfiles AS $oldFileID)
{
	$url = str_replace(".jpg", "", $image2);
	$url = str_replace(".png", "", $url);
	$url = str_replace("|", "/", $url);
	
	$query = sprintf(
		"	UPDATE			banners
			SET				banners.url = '%s'
			WHERE			banners.bannerID = %d",
		$url,
		$oldFileID
	);
	$db->query($query);
	
	$file = __DIR__ . '/banners/fietsen_middel/' . $image2;
	$oldfile = $_SERVER['DOCUMENT_ROOT'] . '/library/media/banners/' . $oldFileID . '.jpg';
	
	$content = file_get_contents($file);
	file_put_contents($oldfile, $content);
}




/* *********************************************************************************************** */




// Fietsen breed

$files = array();

if($handle = opendir(__DIR__ . '/banners/fietsen_breed/')) 
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

// NL, EN, DE
$oldfiles = array(44, 96, 112);

foreach($oldfiles AS $oldFileID)
{
	$oldfile = $_SERVER['DOCUMENT_ROOT'] . '/library/media/banners/' . $oldFileID . '.jpg';
	
	if($oldFileID == 44)
	{
		$image = array_rand($files);
		$image = $files[$image];
		
		$file = __DIR__ . '/banners/fietsen_breed/' . $image;
		
		if(file_get_contents($oldfile) == file_get_contents($file))
		{
			$image = array_rand($files);
			$image = $files[$image];
			
			$file = __DIR__ . '/banners/fietsen_breed/' . $image;
		}
		
		$url = str_replace(".jpg", "", $image);
		$url = str_replace(".png", "", $url);
		$url = str_replace("|", "/", $url);
	}
	
	$query = sprintf(
		"	UPDATE			banners
			SET				banners.url = '%s'
			WHERE			banners.bannerID = %d",
		$url,
		$oldFileID
	);
	$db->query($query);
	
	$content = file_get_contents($file);
	file_put_contents($oldfile, $content);
}




/* *********************************************************************************************** */




// Accessoires groot

$files = array();

if($handle = opendir(__DIR__ . '/banners/accessoires_groot/')) 
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

// NL, EN, DE
$oldfiles = array(45, 97, 113);

foreach($oldfiles AS $oldFileID)
{
	$oldfile = $_SERVER['DOCUMENT_ROOT'] . '/library/media/banners/' . $oldFileID . '.jpg';
	
	if($oldFileID == 45)
	{
		$image = array_rand($files);
		$image = $files[$image];
		
		$file = __DIR__ . '/banners/accessoires_groot/' . $image;
		
		if(file_get_contents($oldfile) == file_get_contents($file))
		{
			$image = array_rand($files);
			$image = $files[$image];
			
			$file = __DIR__ . '/banners/accessoires_groot/' . $image;
		}
		
		$url = str_replace(".jpg", "", $image);
		$url = str_replace(".png", "", $url);
		$url = str_replace("|", "/", $url);
	}
	
	$query = sprintf(
		"	UPDATE			banners
			SET				banners.url = '%s'
			WHERE			banners.bannerID = %d",
		$url,
		$oldFileID
	);
	$db->query($query);
	
	$content = file_get_contents($file);
	file_put_contents($oldfile, $content);
}



/* *********************************************************************************************** */




// Accessoires middel

$files = array();

if($handle = opendir(__DIR__ . '/banners/accessoires_middel/')) 
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

// Middel 1
// NL, EN, DE
$oldfiles = array(46, 98, 114);

foreach($oldfiles AS $oldFileID)
{
	$query = sprintf(
		"	UPDATE			banners
			SET				banners.url = '%s'
			WHERE			banners.bannerID = %d",
		$url,
		$oldFileID
	);
	$db->query($query);
	
	$file = __DIR__ . '/banners/accessoires_middel/' . $image1;
	$oldfile = $_SERVER['DOCUMENT_ROOT'] . '/library/media/banners/' . $oldFileID . '.jpg';
	
	$content = file_get_contents($file);
	file_put_contents($oldfile, $content);
}

// Middel 2
// NL, EN, DE
$oldfiles = array(47, 99, 115);

foreach($oldfiles AS $oldFileID)
{
	$url = str_replace(".jpg", "", $image2);
	$url = str_replace(".png", "", $url);
	$url = str_replace("|", "/", $url);
	
	$query = sprintf(
		"	UPDATE			banners
			SET				banners.url = '%s'
			WHERE			banners.bannerID = %d",
		$url,
		$oldFileID
	);
	$db->query($query);
	
	$file = __DIR__ . '/banners/accessoires_middel/' . $image2;
	$oldfile = $_SERVER['DOCUMENT_ROOT'] . '/library/media/banners/' . $oldFileID . '.jpg';
	
	$content = file_get_contents($file);
	file_put_contents($oldfile, $content);
}




/* *********************************************************************************************** */




// Accessoires breed

$files = array();

if($handle = opendir(__DIR__ . '/banners/accessoires_breed/')) 
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

// NL, EN, DE
$oldfiles = array(48, 100, 116);

foreach($oldfiles AS $oldFileID)
{
	$oldfile = $_SERVER['DOCUMENT_ROOT'] . '/library/media/banners/' . $oldFileID . '.jpg';
	
	if($oldFileID == 48)
	{
		$image = array_rand($files);
		$image = $files[$image];
		
		$file = __DIR__ . '/banners/accessoires_breed/' . $image;
		
		if(file_get_contents($oldfile) == file_get_contents($file))
		{
			$image = array_rand($files);
			$image = $files[$image];
			
			$file = __DIR__ . '/banners/accessoires_breed/' . $image;
		}
		
		$url = str_replace(".jpg", "", $image);
		$url = str_replace(".png", "", $url);
		$url = str_replace("|", "/", $url);
	}
	
	$query = sprintf(
		"	UPDATE			banners
			SET				banners.url = '%s'
			WHERE			banners.bannerID = %d",
		$url,
		$oldFileID
	);
	$db->query($query);
	
	$content = file_get_contents($file);
	file_put_contents($oldfile, $content);
}




/* *********************************************************************************************** */




// Onderdelen groot

$files = array();

if($handle = opendir(__DIR__ . '/banners/onderdelen_groot/')) 
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

// NL, EN, DE
$oldfiles = array(52, 101, 117);

foreach($oldfiles AS $oldFileID)
{
	$oldfile = $_SERVER['DOCUMENT_ROOT'] . '/library/media/banners/' . $oldFileID . '.jpg';
	
	if($oldFileID == 52)
	{
		$image = array_rand($files);
		$image = $files[$image];
		
		$file = __DIR__ . '/banners/onderdelen_groot/' . $image;
		
		if(file_get_contents($oldfile) == file_get_contents($file))
		{
			$image = array_rand($files);
			$image = $files[$image];
			
			$file = __DIR__ . '/banners/onderdelen_groot/' . $image;
		}
		
		$url = str_replace(".jpg", "", $image);
		$url = str_replace(".png", "", $url);
		$url = str_replace("|", "/", $url);
	}
	
	$query = sprintf(
		"	UPDATE			banners
			SET				banners.url = '%s'
			WHERE			banners.bannerID = %d",
		$url,
		$oldFileID
	);
	$db->query($query);
	
	$content = file_get_contents($file);
	file_put_contents($oldfile, $content);
}



/* *********************************************************************************************** */




// Onderdelen middel

$files = array();

if($handle = opendir(__DIR__ . '/banners/onderdelen_middel/')) 
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

// Middel 1
// NL, EN, DE
$oldfiles = array(240, 241, 242);

foreach($oldfiles AS $oldFileID)
{
	$query = sprintf(
		"	UPDATE			banners
			SET				banners.url = '%s'
			WHERE			banners.bannerID = %d",
		$url,
		$oldFileID
	);
	$db->query($query);
	
	$file = __DIR__ . '/banners/onderdelen_middel/' . $image1;
	$oldfile = $_SERVER['DOCUMENT_ROOT'] . '/library/media/banners/' . $oldFileID . '.jpg';
	
	$content = file_get_contents($file);
	file_put_contents($oldfile, $content);
}

// Middel 2
// NL, EN, DE
$oldfiles = array(243, 244, 245);

foreach($oldfiles AS $oldFileID)
{
	$url = str_replace(".jpg", "", $image2);
	$url = str_replace(".png", "", $url);
	$url = str_replace("|", "/", $url);
	
	$query = sprintf(
		"	UPDATE			banners
			SET				banners.url = '%s'
			WHERE			banners.bannerID = %d",
		$url,
		$oldFileID
	);
	$db->query($query);
	
	$file = __DIR__ . '/banners/onderdelen_middel/' . $image2;
	$oldfile = $_SERVER['DOCUMENT_ROOT'] . '/library/media/banners/' . $oldFileID . '.jpg';
	
	$content = file_get_contents($file);
	file_put_contents($oldfile, $content);
}




/* *********************************************************************************************** */




// Onderdelen breed

$files = array();

if($handle = opendir(__DIR__ . '/banners/onderdelen_breed/')) 
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

// NL, EN, DE
$oldfiles = array(246, 247, 248);

foreach($oldfiles AS $oldFileID)
{
	$oldfile = $_SERVER['DOCUMENT_ROOT'] . '/library/media/banners/' . $oldFileID . '.jpg';
	
	if($oldFileID == 246)
	{
		$image = array_rand($files);
		$image = $files[$image];
		
		$file = __DIR__ . '/banners/onderdelen_breed/' . $image;
		
		if(file_get_contents($oldfile) == file_get_contents($file))
		{
			$image = array_rand($files);
			$image = $files[$image];
			
			$file = __DIR__ . '/banners/onderdelen_breed/' . $image;
		}
		
		$url = str_replace(".jpg", "", $image);
		$url = str_replace(".png", "", $url);
		$url = str_replace("|", "/", $url);
	}
	
	$query = sprintf(
		"	UPDATE			banners
			SET				banners.url = '%s'
			WHERE			banners.bannerID = %d",
		$url,
		$oldFileID
	);
	$db->query($query);
	
	$content = file_get_contents($file);
	file_put_contents($oldfile, $content);
}
?>
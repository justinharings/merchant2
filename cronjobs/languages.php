<?php
if(!isset($_SESSION))
{
	session_start();
}


function replaceWords($correct, $incorrect)
{
	define("_LANGUAGE_PACK", "nl");

	$_SERVER['DOCUMENT_ROOT'] = "/var/www/vhosts/justinharings.nl/dev.justinharings.nl";
	
	require_once("/var/www/vhosts/justinharings.nl/dev.justinharings.nl/library/php/classes/motherboard.php");
	
	$mb = new motherboard();

	$return = "";

	foreach($incorrect AS $word)
	{
		$query = sprintf(
			"	SELECT		products_lang.*
				FROM		products_lang
				WHERE		products_lang.name LIKE ('%%%s%%')",
			$word
		);
		$result = $mb->query($query);
		
		while($row = $mb->fetch_assoc($result))
		{
			if(isset($correct[strtolower($row['code'])]) && $correct[strtolower($row['code'])] != "")
			{
				$name = str_replace($word, $correct[strtolower($row['code'])], $row['name']);
				$name = str_replace(ucfirst($word), $correct[strtolower($row['code'])], $name);
				$name = str_replace(strtoupper($word), $correct[strtolower($row['code'])], $name);
				
				$return .= "<span style='color: red'>" . $row['name'] . "</span> => <span style='color: green;'>" . $name . "</span><br/>";
				
				$query = sprintf(
					"	UPDATE		products_lang
						SET			products_lang.name = '%s'
						WHERE		products_lang.languageID = %d",
					$name,
					$row['languageID']
				);
				$mb->query($query);
			}
		}
	}
	
	return $return;
}



// Mat-zwart
$correct['de'] = "mattschwarz";
$correct['en'] = "matt-black";

$incorrect = array(
	"matschwarz",
	"mat-black"
);

print replaceWords($correct, $incorrect) . "<br/><br/>";



// Mat-blauw
$correct['de'] = "matt blau";
$correct['en'] = "matt-blue";

$incorrect = array(
	"mattes blau",
	"mat blau",
	"mattblau",
	"matblau",
	"mat-blue",
	"mat blue"
);

print replaceWords($correct, $incorrect) . "<br/><br/>";



// Mat-bruin
$correct['de'] = "matt braun";
$correct['en'] = "matt-brown";

$incorrect = array(
	"mattes braun",
	"mat braun",
	"mattbraun",
	"matbraun",
	"mat-brown",
	"mat brown"
);

print replaceWords($correct, $incorrect) . "<br/><br/>";



// Mat-grijs
$correct['de'] = "matt grau";
$correct['en'] = "matt-gray";

$incorrect = array(
	"mattes grau",
	"mat grau",
	"mattgrau",
	"matgrau",
	"mat-gray",
	"mat gray"
);

print replaceWords($correct, $incorrect) . "<br/><br/>";



// Mat-grijs
$correct['de'] = "mattes rot";
$correct['en'] = "matt-red";

$incorrect = array(
	"mattrot",
	"mat rot",
	"matt rot",
	"matrot",
	"mat-red",
	"mat red"
);

print replaceWords($correct, $incorrect) . "<br/><br/>";



exit;
?>
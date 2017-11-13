<?php
$array = file_get_contents("http://hoopfietsen.nl/index.php?user=Haringsvof&passw=Welkom0!&option=com_rsfiles&task=rsfiles.download&path=hoopfietsen_products_export.xml&Itemid=689");
$array = new SimpleXMLElement($array);

foreach($array as $element) 
{
	foreach($element as $key => $val) 
	{
		echo "{$key}: {$val}<br/>";
	}
	
	echo "<Br/><Br/>";
}

print "<br/><Br/>Done.";
?>
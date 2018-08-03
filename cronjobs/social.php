<?php
// Instagram accounts
$accounts = array(
	"harings2wielers",
	"haringsvuurwerk",
	"kingmadieren"
);

foreach($accounts AS $account)
{
	$raw = file_get_contents('https://www.instagram.com/' . $account);
	preg_match('/\"followed_by\"\:\s?\{\"count\"\:\s?([0-9]+)/', $raw, $m);
	
	try
	{
		file_put_contents("/var/www/vhosts/justinharings.nl/merchant.justinharings.nl/library/txt/instagram_" . $account . ".txt", intval($m[1]));
	}
	catch (Exception $e)
	{ }
}



// Facebook accounts
$accounts = array(
	"harings2wielers",
	"haringsvuurwerk",
	"kingmadieren"
);

foreach($accounts AS $account)
{
	$fb = file_get_contents("https://graph.facebook.com/" . $account . "/?fields=fan_count&access_token=1122266441142292|eRlIlb7rtRl09X62eyhnO5DYSiA");
	$fb = json_decode($fb, true);
	
	try
	{
		file_put_contents("/var/www/vhosts/justinharings.nl/merchant.justinharings.nl/library/txt/facebook_" . $account . ".txt", intval($fb['fan_count']));
	}
	catch (Exception $e)
	{ }
}



// Twitter accounts
$accounts = array(
	"harings2wielers",
	"haringsvuurwerk"
);

foreach($accounts AS $account)
{
	$tw = file_get_contents('https://cdn.syndication.twimg.com/widgets/followbutton/info.json?screen_names=' . $account); 
	$tw = json_decode($tw, true);
	
	try
	{
		file_put_contents("/var/www/vhosts/justinharings.nl/merchant.justinharings.nl/library/txt/twitter_" . $account . ".txt", intval($tw[0]['followers_count']));
	}
	catch (Exception $e)
	{ }
}
?>
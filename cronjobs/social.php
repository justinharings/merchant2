<?php
// Instagram accounts

$accounts = array(
	"harings2wielers",
	"haringsvuurwerk"
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
	"haringsvuurwerk"
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


/*	***************************************
**	New chapter:
**	Get Facebook followers  @harings2wielers.
**************************************** */

$fb = file_get_contents("https://graph.facebook.com/shirtcollection.nl/?fields=fan_count&access_token=1122266441142292|eRlIlb7rtRl09X62eyhnO5DYSiA");
$fb = json_decode($fb, true);

try
{
	file_put_contents("/var/www/vhosts/justinharings.nl/shirtcollection.nl/library/txt/facebook.txt", intval($fb['fan_count']));
	
	echo "There are " . intval($fb['fan_count']) . " Facebook followers on @shirtcollection.nl. File is updated.<br/><br/>";
	$_succes++;
}
catch (Exception $e)
{
	echo "Facebook followers can't be updated for @shirtcollection.nl.<br/><br/>";
	$_warnings++;
}



/*	***************************************
**	New chapter:
**	Get Twitter followers  @fireworksforall.
**************************************** */

$tw = file_get_contents('https://cdn.syndication.twimg.com/widgets/followbutton/info.json?screen_names=fireworksforall'); 
$tw = json_decode($tw, true);

try
{
	file_put_contents("/var/www/vhosts/justinharings.nl/fireworks4all.nl/library/txt/twitter.txt", intval($tw[0]['followers_count']));
	
	echo "There are " . intval($tw[0]['followers_count']) . " Twitter followers on @fireworksforall. File is updated.<br/><br/>";
	$_succes++;
}
catch (Exception $e)
{
	echo "Twitter followers can't be updated for @fireworksforall.<br/><br/>";
	$_warnings++;
}



/*	***************************************
**	New chapter:
**	Get Facebook followers  @Fireworksforall.
**************************************** */

$fb = file_get_contents("https://graph.facebook.com/Fireworksforall/?fields=fan_count&access_token=1122266441142292|eRlIlb7rtRl09X62eyhnO5DYSiA");
$fb = json_decode($fb, true);

try
{
	file_put_contents("/var/www/vhosts/justinharings.nl/fireworks4all.nl/library/txt/facebook.txt", intval($fb['fan_count']));
	
	echo "There are " . intval($fb['fan_count']) . " Facebook followers on @Fireworksforall. File is updated.<br/><br/>";
	$_succes++;
}
catch (Exception $e)
{
	echo "Facebook followers can't be updated for @Fireworksforall.<br/><br/>";
	$_warnings++;
}






/*	***************************************
**	New chapter:
**	Get Youtube followers  @fireworks4all.
**************************************** */

$channel_id = "UCRY6LG_XoZIg7ZBM6B1htug";
$api_key = "AIzaSyDyh-givJgqsftwuZ-QhVwtcMe6uGb3kco";
$api_response = file_get_contents('https://www.googleapis.com/youtube/v3/channels?part=statistics&id='.$channel_id.'&fields=items/statistics/subscriberCount&key='.$api_key);
$api_response_decoded = json_decode($api_response, true);

try
{
	file_put_contents("/var/www/vhosts/justinharings.nl/fireworks4all.nl/library/txt/youtube.txt", intval($api_response_decoded['items'][0]['statistics']['subscriberCount']));
	
	echo "There are " . intval($api_response_decoded['items'][0]['statistics']['subscriberCount']) . " Youtube followers on @fireworks4all.nl. File is updated.<br/><br/>";
	$_succes++;
}
catch (Exception $e)
{
	echo "Youtube followers can't be updated for @fireworks4all.nl.<br/><br/>";
	$_warnings++;
}
?>
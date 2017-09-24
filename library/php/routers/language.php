<?php
/*
**	The default language pack is loaded
**	when there is no language given.
*/

$_default_language = "NL";



if(!isset($_GET['language_pack']))
{
	/*
	**	No language is set. Go to the map
	**	view. The visitor can choose a country
	**	from the map.
	*/
	
	if(substr($_SERVER['HTTP_HOST'], -1) != "/")
	{
		$_SERVER['HTTP_HOST'] .= "/";
	}
	
	header("location: http://$_SERVER[HTTP_HOST]" . strtolower($_default_language) . "/");
}
else
{
	/*
	**	Array with the languages we support.
	**	After this we check of the requested
	**	language pack is supported. If not,
	**	redirect the visitor to the map.
	*/
	
	$_recognized_languages = array(
		"NL"	// Netherlands
	);
	
	
	
	/*
	**	Languages written as full name for the
	**	website to use. Returned as array.
	*/
	
	$_full_name_languages = array(
		"Nederlands" => "NL"
	);
	
	
	
	
	if(isset($_GET['language_pack']) && in_array(strtoupper($_GET['language_pack']), $_recognized_languages))
	{
		/*
		**	The language is found. Load the language
		**	pack and set some information in order
		**	for Google to index the right information.
		*/
		
		define("_LANGUAGE_PACK", $_GET['language_pack']);
		$_SESSION['_LANGUAGE_PACK'] = _LANGUAGE_PACK;
	}
	else
	{
		if(defined("_DEVELOPMENT_ENVIRONMENT") && _DEVELOPMENT_ENVIRONMENT == true)
		{
			die("Requested language pack <em>" . $_GET['language_pack'] . "</em> is not supported.");
		}
		else
		{
			header("location: http://$_SERVER[HTTP_HOST]/" . strtolower($_default_language) . "/");
		}
	}
}
?>
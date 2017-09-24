<?php
class head extends motherboard
{
	/*
	**	Generate the website's <title/> tag. Use given information
	**	as the shown webshop category, service page or anything
	**	like that. If there is no special page active, show the default title.
	*/
	
	public function title()
	{
		$xml = simplexml_load_string($this->language_xml);
		print $xml->html_head->default_title;
	}
	
	
	
	/*
	**	Generate the website's <description/> tag. Use given information
	**	as the shown webshop category, service page or anything
	**	like that. If there is no special page active, show the default description.
	*/
	
	public function description()
	{
		$xml = simplexml_load_string($this->language_xml);
		print $xml->html_head->default_description;
	}



	/*
	**	Generate the website's <keywords/> tag. Use given information
	**	as the shown webshop category, service page or anything
	**	like that. If there is no special page active, show the default keywords.
	*/
		
	public function keywords()
	{
		$xml = simplexml_load_string($this->language_xml);
		print $xml->html_head->default_keywords;
	}
}
?>
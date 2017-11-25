<?php
class merchant extends motherboard
{
	/*
	**
	*/
	
	public function view()
	{
		$query = sprintf(
			"	SELECT		merchant.*
				FROM		merchant"
		);
		$result = parent::query($query);
		
		return parent::fetch_array($result);
	}
	
	
	
	/*
	**	Load a merchant from the database.
	**	data[0]	=	merchantID.
	*/
	
	public function load($data)
	{
		parent::_checkInputValues($data, 1);
		
		$query = sprintf(
			"	SELECT		*
				FROM		merchant
				WHERE		merchant.merchantID = %d",
			$data[0]
		);
		$result = parent::query($query);
		$return = parent::fetch_assoc($result);
		
		$return['street'] = "";
		$return['housenumber'] = "";
		
		if(preg_match('/(?P<address>[^\d]+) (?P<number>\d+.?)/', $return['address'], $matches))
		{
			$return['street'] = $matches['address'];
			$return['housenumber'] = $matches['number'];
		}
		
		return $return;
	}
}
?>
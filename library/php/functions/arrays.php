<?php
function _search_for_id($id, $array) 
{
	foreach ($array as $fKey => $val) 
	{
		$keys = array_keys($val);
		
		foreach($keys AS $key)
		{
			if($val[$key] == $id)
			{
				return $fKey;
			}
		}
	}
	
	return null;
}
?>
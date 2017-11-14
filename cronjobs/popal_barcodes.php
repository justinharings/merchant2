<?php
$array = file_get_contents("http://plm.popal.nl/webservice/channel/2");
$array = json_decode($array, true);

foreach($array AS $key => $values)
{
	foreach($values['products'] AS $key_p => $value_p)
	{
		foreach($value_p['properties'] AS $key_pr => $value_pr)
		{
			if(is_array($value_pr['value']))
			{
				$value_pr['value'] = $value_pr['value']['nl_nl'];
			}
			
			echo "{$value_pr['name']['nl_nl']}: {$value_pr['value']}<br/>";
		}
		
		foreach($value_p['dimensions'] AS $key_pr => $value_pr)
		{
			if(is_array($value_pr['value']))
			{
				$value_pr['value'] = $value_pr['value']['nl_nl'];
			}
			
			echo "{$value_pr['name']['nl_nl']}: {$value_pr['value']}<br/>";
		}
		
		echo "<Br/><Br/>";
	}
}

print "<br/><Br/>Done.";
?>
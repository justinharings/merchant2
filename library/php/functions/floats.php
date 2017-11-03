<?php
function _frontend_float($float, $currency = "EUR")
{
	$return = "";
	
	if($currency != "GBP")
	{
		setlocale(LC_MONETARY, 'nl_NL');
		$return = money_format('%i', $float);
	}
	else
	{
		setlocale(LC_MONETARY, 'en_GB');
		$return = money_format('%i', $float);
	}
	
	$return = str_replace("EUR", "", $return);
	$return = str_replace("GBP", "", $return);
	$return = str_replace(" ", "", $return);
	
	return $return;
}
?>
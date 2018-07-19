<?php
if(!isset($_SESSION))
{
	session_start();
}


define("_LANGUAGE_PACK", "nl");

$_SERVER['DOCUMENT_ROOT'] = "/var/www/vhosts/justinharings.nl/dev.justinharings.nl";

require_once("/var/www/vhosts/justinharings.nl/dev.justinharings.nl/library/php/classes/motherboard.php");

$mb = new motherboard();




$_merchants = array();

$query = sprintf(
	"	SELECT		merchant.merchantID
		FROM		merchant"
);
$result = $mb->query($query);

while($row = $mb->fetch_assoc($result))
{
	$_merchants[] = $row['merchantID'];
	
	$query = sprintf(
		"	REPLACE INTO		dashboard
			SET					dashboard.merchantID = %d",
		$row['merchantID']
	);
	$mb->query($query);
}



foreach($_merchants AS $merchantID)
{
	/*
	**	Maandelijkse bezoekers grafiek
	*/
	
	$totalVisitorsMonthlyKeys = $mb->_runFunction("dashboard", "totalVisitorsMonthlyKeys");
	$totalVisitorsMonthlyValues = $mb->_runFunction("dashboard", "totalVisitorsMonthlyValues", array($merchantID, "DISTINCT"));
	$totalVisitorHitsMonthlyValues = $mb->_runFunction("dashboard", "totalVisitorsMonthlyValues", array($merchantID, ""));
	
	$query = sprintf(
		"	UPDATE		dashboard
			SET			dashboard.monthly_visitor_graph = '%s'
			WHERE		dashboard.merchantID = %d",
		serialize(array("totalVisitorsMonthlyKeys" => $totalVisitorsMonthlyKeys, "totalVisitorsMonthlyValues" => $totalVisitorsMonthlyValues, "totalVisitorHitsMonthlyValues" => $totalVisitorHitsMonthlyValues)),
		$merchantID
	);	
	$mb->query($query);
	
	
	
	
	/*
	**	Maandelijkse omzet
	*/
	
	$last_month = date("m");
	$last_year = date("Y") - 1;
	
	$profit_current = $mb->_runFunction("reports", "viewArticleGroups", array($merchantID, date("m"), date("Y")));
	$profit_last = $mb->_runFunction("reports", "viewArticleGroups", array($merchantID, $last_month, $last_year));
	
	$cnt = 0;
	
	foreach($profit_current AS $key => $value)
	{
		$cnt += $value['grand_total'];
	}
	
	$profit_current = $cnt;
	
	
	$cnt = 0;
	
	foreach($profit_last AS $key => $value)
	{
		$cnt += $value['grand_total'];
	}
	
	$profit_last = $cnt;
	
	
	$percentage = ceil((($profit_current/$profit_last)*100));
	
	$query = sprintf(
		"	UPDATE		dashboard
			SET			dashboard.monthly_profit = '%s'
			WHERE		dashboard.merchantID = %d",
		serialize(array("profit_current" => $profit_current, "percentage" => $percentage, "last_year" => $last_year)),
		$merchantID
	);	
	$mb->query($query);
	
	
	
	
	/*
	**	Jaarlijkse omzet
	*/
	
	$last_year = date("Y") - 1;
		
	$profit_current = $mb->_runFunction("reports", "viewArticleGroups", array($merchantID, "", date("Y")));
	$profit_last = $mb->_runFunction("reports", "viewArticleGroups", array($merchantID, "", $last_year));
	
	$cnt = 0;
	
	foreach($profit_current AS $key => $value)
	{
		$cnt += $value['grand_total'];
	}
	
	$profit_current = $cnt;
	
	
	$cnt = 0;
	
	foreach($profit_last AS $key => $value)
	{
		$cnt += $value['grand_total'];
	}
	
	$profit_last = $cnt;
	
	$percentage = ceil((($profit_current/$profit_last)*100));
	
	$query = sprintf(
		"	UPDATE		dashboard
			SET			dashboard.yearly_profit = '%s'
			WHERE		dashboard.merchantID = %d",
		serialize(array("profit_current" => $profit_current, "percentage" => $percentage, "last_year" => $last_year)),
		$merchantID
	);	
	$mb->query($query);
	
	
	
	
	/*
	**	Bezoekers deze maand
	*/
	
	$last_month = date("m") - 1;
	$last_year = date("Y");
		
	if($last_month == 0)
	{
		$last_month = 12;
		$last_year -= 1;
	}
		
	$visitors_current = $mb->_runFunction("dashboard", "visitors", array($merchantID, date("Y"), date("m"), "", "DISTINCT"));
	$visitors_last = $mb->_runFunction("dashboard", "visitors", array($merchantID, $last_year, $last_month, "", "DISTINCT"));
	
	$percentage = ceil((($visitors_current/$visitors_last)*100));
	
	$query = sprintf(
		"	UPDATE		dashboard
			SET			dashboard.monthly_visitors = '%s'
			WHERE		dashboard.merchantID = %d",
		serialize(array("visitors_current" => $visitors_current, "percentage" => $percentage)),
		$merchantID
	);	
	$mb->query($query);
	
	
	
	
	/*
	**	Bezoekers informatie
	*/
	
	$countries = $mb->_runFunction("dashboard", "visitorCountries", array($merchantID, date("Y"), date("m"), date("d")));
	
	$query = sprintf(
		"	UPDATE		dashboard
			SET			dashboard.visitor_countries = %d
			WHERE		dashboard.merchantID = %d",
		count($countries),
		$merchantID
	);	
	$mb->query($query);
	
	
	
	/*
	**	Verkoop grafiek
	*/
	
	$salesMonthlyKeys = $mb->_runFunction("dashboard", "salesMonthlyKeys");
	$salesTwoYears = $mb->_runFunction("dashboard", "salesCalc", array($merchantID, date("Y")-2));
	$salesOneYear = $mb->_runFunction("dashboard", "salesCalc", array($merchantID, date("Y")-1));
	$salesThisYear = $mb->_runFunction("dashboard", "salesCalc", array($merchantID, date("Y")));
	
	$query = sprintf(
		"	UPDATE		dashboard
			SET			dashboard.sales_graph = '%s'
			WHERE		dashboard.merchantID = %d",
		serialize(array("salesMonthlyKeys" => $salesMonthlyKeys, "salesTwoYears" => $salesTwoYears, "salesOneYear" => $salesOneYear, "salesThisYear" => $salesThisYear)),
		$merchantID
	);	
	$mb->query($query);
	
	
	
	
	
	$query = sprintf(
		"	UPDATE		dashboard
			SET			dashboard.date_update = NOW()
			WHERE		dashboard.merchantID = %d",
		$merchantID
	);
	$mb->query($query);
}
?>
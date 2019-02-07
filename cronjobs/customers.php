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
}



foreach($_merchants AS $merchantID)
{
	$query = sprintf(
		"	SELECT			*
			FROM			customers
			WHERE			customers.merchantID = %d",
		$merchantID
	);
	$result = $mb->query($query);
	
	$customers = array();
	
	while($row = $mb->fetch_assoc($result))
	{
		if(strlen($row['zip_code']) < 6)
		{
			continue;
		}
		
		$row['street'] = "";
		$row['housenumber'] = "";
		
		if(preg_match('/(?P<address>[^\d]+) (?P<number>\d+.?)/', $row['address'], $matches))
		{
			$row['street'] = $matches['address'];
			$row['housenumber'] = $matches['number'];
		}
		
		$checkcode = strtoupper($row['zip_code'] . $row['housenumber']);
		$checkcode = str_replace(" ", "", $checkcode);
		
		$customers[$checkcode][] = $row['customerID'];
	}
	
	foreach($customers AS $key => $IDArray)
	{
		if(count($IDArray) == 1)
		{
			unset($customers[$key]);
		}
	}
	
	
	$combine = array();
	$num = 0;
	
	foreach($customers AS $key => $IDArray)
	{
		$names = array();
		
		foreach($IDArray AS $customerID)
		{
			$query = sprintf(
				"	SELECT		customers.*
					FROM		customers
					WHERE		customers.customerID = %d",
				$customerID
			);
			$result = $mb->query($query);
			$row = $mb->fetch_assoc($result);
			
			$last_word_start = strrpos($row['name'], " ") + 1;
			$last_word_end = strlen($row['name']) - 1;
			$last_word = substr($row['name'], $last_word_start, $last_word_end);
			
			$names[] = strtolower($last_word);
		}
		
		$names = array_count_values($names);
		$final = array();
		
		foreach($names AS $name => $cnt)
		{
			if(intval($cnt) > 1)
			{
				$final[$name] = $cnt;
			}
		}
		
		if(count($final) == 0)
		{
			unset($final);
		}
		
		if(isset($final))
		{
			foreach($final AS $name => $cnt)
			{
				$query = sprintf(
					"	SELECT		customers.*
						FROM		customers
						WHERE		customers.name LIKE ('%%%s%%')
							AND		customers.customerID IN(%s)",
					$name,
					implode(",", $IDArray)
				);
				$result = $mb->query($query);
				
				
				while($row = $mb->fetch_assoc($result))
				{
					$combine[$num][] = $row['customerID'];
				}
			}
			
			$num++;
		}
	}
	
	foreach($combine AS $key => $customers)
	{
		$keep = 0;
		
		foreach($customers AS $customerID)
		{
			$query = sprintf(
				"	SELECT		customers.customer_code
					FROM		customers
					WHERE		customers.customerID = %d",
				$customerID
			);
			$result = $mb->query($query);
			$row = $mb->fetch_assoc($result);
			
			if($row['customer_code'] != "" && $keep == 0)
			{
				$keep = $customerID;
			}
		}
		
		if($keep == 0)
		{
			$keep = $customers[0];
		}
		
		foreach($customers AS $customerID)
		{
			if($customerID == $keep)
			{
				continue;
			}
			
			$query = sprintf(
				"	UPDATE		orders
					SET			orders.customerID = %d
					WHERE		orders.customerID = %d",
				$keep,
				$customerID
			);
			$mb->query($query);
			
			print "Orders " . $customerID . " => " . $keep . "<br/>";
			
			$query = sprintf(
				"	UPDATE		workorders
					SET			workorders.customerID = %d
					WHERE		workorders.customerID = %d",
				$keep,
				$customerID
			);
			$mb->query($query);
			
			print "Workorders " . $customerID . " => " . $keep . "<br/>";
			
			$query = sprintf(
				"	UPDATE		customers_notes
					SET			customers_notes.customerID = %d
					WHERE		customers_notes.customerID = %d",
				$keep,
				$customerID
			);
			$mb->query($query);
			
			print "Customer notes " . $customerID . " => " . $keep . "<br/>";
			
			$query = sprintf(
				"	UPDATE		mailserver
					SET			mailserver.customerID = %d
					WHERE		mailserver.customerID = %d",
				$keep,
				$customerID
			);
			$mb->query($query);
			
			print "Mailserver " . $customerID . " => " . $keep . "<br/>";
			
			$query = sprintf(
				"	UPDATE		batteries
					SET			batteries.customerID = %d
					WHERE		batteries.customerID = %d",
				$keep,
				$customerID
			);
			$mb->query($query);
			
			print "Batteries " . $customerID . " => " . $keep . "<br/>";
			
			
			$query = sprintf(
				"	DELETE FROM		customers
					WHERE			customers.customerID = %d",
				$customerID
			);
			$mb->query($query);
			
			print "Customer " . $customerID . " deleted.<br/><br/>";
		}
		
		print "<br/><br/>----------------<br/><br/>";
	}
}
?>
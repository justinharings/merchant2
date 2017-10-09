<?php
require_once("/var/www/vhosts/justinharings.nl/httpdocs/merchant/library/php/classes/databaseConnector.php");
require_once(__DIR__ . "/library/php/classes/database.php");

define("_DEVELOPMENT_ENVIRONMENT", true);

$db_old = new databaseConnector();
$db_new = new database();


$merchantID = 1;


$query = sprintf(
	"	DELETE FROM		customers
		WHERE			customers.merchantID = %d",
	$merchantID
);
$db_new->query($query);



$query = sprintf(
	"	SELECT		customers.*
		FROM		customers
		WHERE		customers.merchantID = %d",
	$merchantID
);
$result = $db_old->query($query);

while($row = $db_old->fetchAssoc($result))
{
	if($row['name'] == "" || $row['name'] == "-" || $row['name'] == " " || strpos($row['name'], "asdf") !== false)
	{
		continue;
	}
	
	$phone = "";
	$mobile = "";
	
	if(strpos($row['phone_number'], "06"))
	{
		$mobile = $row['phone_number'];
	}
	else
	{
		$phone = $row['phone_number'];
	}
	
	$row['name'] = str_replace("-", "- ", $row['name']);
	
	$row['name'] = strtolower($row['name']);
	$row['name'] = ucwords($row['name']);
	
	$row['name'] = str_replace("- ", "-", $row['name']);
	
	$row['name'] = str_replace("Fam", "fam", $row['name']);
	$row['name'] = str_replace("Dhr", "dhr", $row['name']);
	$row['name'] = str_replace("Mevr", "mevr", $row['name']);
	$row['name'] = str_replace("Fa.", "fa.", $row['name']);
	$row['name'] = str_replace("V.d.", "v.d.", $row['name']);
	$row['name'] = str_replace("B.v.", "B.V.", $row['name']);
	$row['name'] = str_replace("B&b", "B&B", $row['name']);
	
	$row['name'] = str_replace("dhr ", "dhr. ", $row['name']);
	$row['name'] = str_replace("mevr ", "mevr. ", $row['name']);
	$row['name'] = str_replace("fam ", "fam. ", $row['name']);
	$row['name'] = str_replace("fa ", "fa. ", $row['name']);
	
	$row['name'] = str_replace(" De ", " de ", $row['name']);
	$row['name'] = str_replace(" Van ", " van ", $row['name']);
	$row['name'] = str_replace(" Ter ", " ter ", $row['name']);
	$row['name'] = str_replace(" Ten ", " ten ", $row['name']);
	$row['name'] = str_replace(" Der ", " der ", $row['name']);
	$row['name'] = str_replace(" En ", " en ", $row['name']);
	$row['name'] = str_replace(" In Het ", " in het ", $row['name']);
	$row['name'] = str_replace(" In 't ", " in het ", $row['name']);
	
	if(substr($row['name'], 1, 1) == " ")
	{
		$row['name'] = substr($row['name'], 0, 1) . "." . substr($row['name'], 1, strlen($row['name']));
	}
	
	$row['country'] = str_replace("_", " ", $row['country']);
	$row['country'] = ucwords($row['country']);
	
	$query = sprintf(
		"	INSERT INTO		customers
			SET				customers.customerID = %d,
							customers.merchantID = %d,
							customers.name = '%s',
							customers.company = '%s',
							customers.address = '%s',
							customers.zip_code = '%s',
							customers.city = '%s',
							customers.country = '%s',
							customers.phone = '%s',
							customers.mobile_phone = '%s',
							customers.email_address = '%s',
							customers.customer_code = '%s',
							customers.date_added = '%s'",
		$row['customerID'],
		$row['merchantID'],
		$db_new->real_escape_string($row['name']),
		$db_new->real_escape_string($row['second_name']),
		$db_new->real_escape_string($row['address']),
		$db_new->real_escape_string($row['zipcode']),
		$db_new->real_escape_string(ucfirst(strtolower($row['city']))),
		$db_new->real_escape_string($row['country']),
		$db_new->real_escape_string($phone),
		$db_new->real_escape_string($mobile),
		$db_new->real_escape_string($row['email_address']),
		"",
		$row['date_time']
	);
	$db_new->query($query);
}

print "Done.";
?>

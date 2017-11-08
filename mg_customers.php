<?php
exit;

require_once("/var/www/vhosts/justinharings.nl/httpdocs/merchant/library/php/classes/databaseConnector.php");
require_once("/var/www/vhosts/justinharings.nl/merchant.justinharings.nl/library/php/classes/database.php");

$old_db = new databaseConnector();
$new_db = new database();

$query = sprintf(
	"	SELECT			*
		FROM			customers
		WHERE			customers.merchantID = 2"
);
$result = $old_db->query($query);

while($row = $old_db->fetchAssoc($result))
{
	$phone = "";
	$mobile = "";
	
	if($row['phone_number'] != "")
	{
		if(strpos($row['phone_number'], "06"))
		{
			$mobile = $row['phone_number'];
		}
		else
		{
			$phone = $row['phone_number'];
		}
	}
	
	$query2 = sprintf(
		"	INSERT INTO		customers
			SET				customers.merchantID = 3,
							customers.name = '%s',
							customers.company = '%s',
							customers.address = '%s',
							customers.zip_code = '%s',
							customers.city = '%s',
							customers.country = '%s',
							customers.phone = '%s',
							customers.mobile_phone = '%s',
							customers.email_address = '%s',
							customers.customer_code = '',
							customers.date_added = NOW()",
		$new_db->real_escape_string($row['name']),
		$new_db->real_escape_string($row['second_name']),
		$new_db->real_escape_string($row['address']),
		$new_db->real_escape_string($row['zipcode']),
		$new_db->real_escape_string($row['city']),
		$new_db->real_escape_string($row['country']),
		$new_db->real_escape_string($phone),
		$new_db->real_escape_string($mobile),
		$new_db->real_escape_string($row['email_address'])
	);
	$new_db->query($query2);
}
?>
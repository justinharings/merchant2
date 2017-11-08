<?php
exit;

require_once("/var/www/vhosts/justinharings.nl/httpdocs/merchant/library/php/classes/databaseConnector.php");
require_once("/var/www/vhosts/justinharings.nl/merchant.justinharings.nl/library/php/classes/database.php");

$old_db = new databaseConnector();
$new_db = new database();


$query = sprintf(
	"	DELETE FROM		orders
		WHERE			orders.merchantID = 3"
);
$new_db->query($query);


$query = sprintf(
	"	SELECT		orders.*
		FROM		orders
		WHERE		orders.orderID = 2"
);
$result = $old_db->query($query);

while($row = $old_db->fetchAssoc($result))
{
	
}

print "Done.";
?>
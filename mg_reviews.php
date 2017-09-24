<?php
require_once("/var/www/vhosts/justinharings.nl/httpdocs/merchant/library/php/classes/databaseConnector.php");
require_once(__DIR__ . "/library/php/classes/database.php");


define("_DEVELOPMENT_ENVIRONMENT", true);

$db_old = new databaseConnector();
$db_new = new database();


$merchantID = 1;


$query = sprintf(
	"	DELETE FROM		reviews
		WHERE			reviews.merchantID = %d",
	$merchantID
);
$db_new->query($query);


$query = sprintf(
	"	SELECT		reviews.*
		FROM		reviews
		WHERE		reviews.merchantID = %d",
	$merchantID
);
$result = $db_old->query($query);

while($row = $db_old->fetchAssoc($result))
{
	$query_insert = sprintf(
		"	INSERT INTO		reviews
			SET				reviews.merchantID = %d,
							reviews.productID = %d,
							reviews.approved = %d,
							reviews.name = '%s',
							reviews.stars = %d,
							reviews.description = '%s',
							reviews.date_added = '%s'",
		$row['merchantID'],
		$row['productID'],
		$row['approved'],
		$db_new->real_escape_string($row['name']),
		$row['stars'],
		$db_new->real_escape_string($row['good']),
		$row['date_time']
	);
	$db_new->query($query_insert);
}

print "done.";
?>
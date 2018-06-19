<?php
if(!isset($_SESSION))
{
	session_start();
}

define("_LANGUAGE_PACK", "nl");

require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/classes/motherboard.php");
	
$mb = new motherboard();

$query = sprintf(
	"	INSERT INTO		assistent_hold
		SET				assistent_hold.orderID = %d,
						assistent_hold.date_time = NOW()",
	intval($_GET['orderID'])
);
$mb->query($query);
?>

<script type="text/javascript">
	parent.document.location.href = '/assistent/';
</script>
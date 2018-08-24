<?php
if(!isset($_SESSION))
{
	session_start();
}

define("_LANGUAGE_PACK", "nl");

require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/classes/motherboard.php");
	
$mb = new motherboard();

$query = sprintf(
	"	INSERT INTO		assistent_stock_watchlist
		SET				assistent_stock_watchlist.productID = %d",
	intval($_POST['productID'])
);
$mb->query($query);

$query = sprintf(
	"	DELETE FROM		assistent_stock
		WHERE			assistent_stock.stockID = %d",
	intval($_POST['stockID'])
);
$mb->query($query);
?>

<script type="text/javascript">
	parent.document.location.href = '/assistent/';
</script>
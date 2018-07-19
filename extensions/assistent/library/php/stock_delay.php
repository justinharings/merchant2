<?php
if(!isset($_SESSION))
{
	session_start();
}

define("_LANGUAGE_PACK", "nl");

require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/classes/motherboard.php");
	
$mb = new motherboard();

$query = sprintf(
	"	UPDATE			assistent_stock
		SET				assistent_stock.deleted = 1,
						assistent_stock.delay = NOW()
		WHERE			assistent_stock.stockID = %d",
	intval($_POST['stockID'])
);
$mb->query($query);
?>

<script type="text/javascript">
	parent.document.location.href = '/assistent/';
</script>
<?php
if(!isset($_SESSION))
{
	session_start();
}

define("_LANGUAGE_PACK", "nl");

require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/classes/motherboard.php");
	
$mb = new motherboard();
$review = $mb->_runFunction("reviews", "load", array($_POST['reviewID']));

$review['approved'] = 1;

$mb->_runFunction("reviews", "save", array(1, $review));
?>

<script type="text/javascript">
	parent.document.location.href = '/assistent/';
</script>
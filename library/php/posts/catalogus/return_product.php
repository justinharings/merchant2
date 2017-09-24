<?php
if(!isset($_SESSION))
{
	session_start();
}


require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/classes/motherboard.php");

$mb = new motherboard();

if(isset($_POST['article_code']))
{
	$return = $mb->_runFunction(
		"categories",
		"returnProductBasedOnArticleCode",
		array(
			$_SESSION['merchantID'],
			$_POST
		)
	);
}
else
{
	$return = $mb->_runFunction(
		"categories",
		"returnProductBasedOnID",
		array(
			$_SESSION['merchantID'],
			$_POST
		)
	);
}

print json_encode($return);
?>
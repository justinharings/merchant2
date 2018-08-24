<?php
if(!isset($_SESSION))
{
	session_start();
}



require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/classes/motherboard.php");

$mb = new motherboard();

if($_POST['returnURL'] == "block")
{
	$query = sprintf(
		"	UPDATE		products
			SET			products.deleted = 1
			WHERE		products.barcode = '%s'",
		$_POST['barcode']
	);
	$mb->query($query);
}


$merchantID = $_SESSION['merchantID'];

if(isset($_POST['merchantID']))
{
	$merchantID = $_POST['merchantID'];
	$_SESSION['merchantID'] = $merchantID;
}

$return = $mb->_runFunction(
	"products",
	"save",
	array(
		$merchantID,
		$_POST,
		$_FILES
	)
);

if($_POST['returnURL'] != "block")
{
	// Replace the ID of the inserted or updated form.
	$_POST['returnURL'] = str_replace("[dataID]", intval($return), $_POST['returnURL']);
	
	if(intval($return) >= 0 && isset($_POST['returnURL']))
	{
		header("location: " . $_POST['returnURL']);
		exit;
	}
	
	$mb->_throwUserError();
}
else
{
	print $return;
}
?>
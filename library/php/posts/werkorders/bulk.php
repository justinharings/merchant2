<?php
if(!isset($_SESSION))
{
	session_start();
}



require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/classes/motherboard.php");

$mb = new motherboard();

$data = $mb->_runFunction("workorders", "view", array($_SESSION['merchantID'], "", "workorders.expiration_date ASC, workorders.priority DESC, workorders.date_added ASC", "0,100"));

if($mb->num_rows($data))
{
	$num = 0;
	
	foreach($data AS $value)
	{
		if($value['status'] != 1)
		{
			continue;
		}
		
		$return = $mb->_runFunction(
			"mailserver",
			"sendSms",
			array(
				$_SESSION['merchantID'],
				$value['phone_number'],
				$_POST['content'],
				0,
				$value['workorderID'],
				0
			)
		);
	}
}

if(isset($_POST['returnURL']))
{
	header("location: " . $_POST['returnURL']);
	exit;
}
?>
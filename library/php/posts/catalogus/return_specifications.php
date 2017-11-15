<?php
if(!isset($_SESSION))
{
	session_start();
}


require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/classes/motherboard.php");

$mb = new motherboard();

$return = $mb->_runFunction(
	"categories",
	"loadSpecification",
	array(
		$_POST['specificationID']
	)
);

$array = array();
$num = 0;

foreach($return['filters'] AS $value)
{
	$array[$num]['language'] = $value['language'];
	$array[$num]['key'] = $value['key'];
	$array[$num]['value'] = $value['value'];
	
	$num++;
}

print json_encode($array);
?>
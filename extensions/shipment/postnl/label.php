<?php
/* MERCHANT INFO */

if(!isset($_SESSION))
{
	session_start();
}

define("_LANGUAGE_PACK", $_SESSION['language_pack']);
	
require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/classes/motherboard.php");

$mb = new motherboard();
$data1 = $mb->_runfunction("merchant", "load", array($_SESSION['merchantID']));
$data2 = $mb->_runFunction("postnl", "load", array($_SESSION['merchantID']));
$data3 = $mb->_runFunction("orders", "load", array(intval($_GET['orderID'])));

$data3['customer']['housenumber_add'] = preg_replace("/[^a-zA-Z]+/", "", $data3['customer']['housenumber']);
$data3['customer']['housenumber_add'][0];

$data3['customer']['housenumber'] = intval($data3['customer']['housenumber']);

if	(
		$data2['customer_code'] == ""
		|| $data2['customer_number'] == ""
		|| $data2['contactperson'] == ""
		|| $data2['api_key'] == ""
	)
{
	$mb->_throwUserError();
}

$data1['street'] = "";
$data1['housenumber'] = "";

if(preg_match('/(?P<address>[^\d]+) (?P<number>\d+.?)/', $data1['address'], $matches))
{
	$data1['street'] = $matches['address'];
	$data1['housenumber'] = $matches['number'];
}



/* POSTNL API */

use ThirtyBees\PostNL\Entity\Label;
use ThirtyBees\PostNL\PostNL;
use ThirtyBees\PostNL\Entity\Customer;
use ThirtyBees\PostNL\Entity\Address;
use ThirtyBees\PostNL\Entity\Shipment;
use ThirtyBees\PostNL\Entity\Dimension;

require_once __DIR__ . '/vendor/autoload.php';

$country_code = $mb->_countryCodes($data3['customer']['country']);

if($country_code == "NL")
{
	$ProductCodeDelivery = 3085;
}
else if($country_code == "BE")
{
	$ProductCodeDelivery = 4944;
}
else
{
	$ProductCodeDelivery = 4945;
}

$customer = Customer::create(
	[
	    'CollectionLocation' => "12345",
	    'CustomerCode'       => $data2['customer_code'],
	    'CustomerNumber'     => $data2['customer_number'],
	    'ContactPerson'      => $data2['contactperson'],
	    'Email'              => $data1['email_address'],
	    'Name'               => $data2['contactperson'],
	    'Address'            => Address::create(
	    	[
		        'AddressType' => '02',
		        'City'        => $data1['city'],
		        'CompanyName' => $data1['company_name'],
		        'Countrycode' => 'NL',
		        'HouseNr'     => $data1['housenumber'],
		        'Street'      => $data1['street'],
		        'Zipcode'     => $data1['zip_code'],
			]
		)
	]
);

$apikey = $data2['api_key'];
$sandbox = true;

$postnl = new PostNL($customer, $apikey, $sandbox, PostNL::MODE_SOAP);

$barcodes = $postnl->generateBarcodesByCountryCodes([$country_code => 2]);

$shipments = [
    Shipment::create(
    	[
	        'Addresses'           => [
	            Address::create(
	            	[
		                'AddressType' => '01',
		                'City'        => ucfirst($data3['customer']['city']),
		                'Countrycode' => $country_code,
		                'FirstName'   => '',
		                'HouseNr'     => $data3['customer']['housenumber'],
		                'HouseNrExt'  => $data3['customer']['housenumber_add'],
		                'Name'        => $data3['customer']['name'],
		                'Street'      => $data3['customer']['street'],
		                'Zipcode'     => $data3['customer']['zip_code'],
					]
				),
	        ],
	        'Barcode'             => $barcodes[$country_code][0],
	        'Dimension'           => new Dimension('1000'),
	        'ProductCodeDelivery' => $ProductCodeDelivery,
		]
	)
];

$label = $postnl->generateLabels(
    $shipments,
    'GraphicFile|PDF',
    true, 				// Confirm immediately
    Label::FORMAT_A4, 	// Format -- this merges multiple A6 labels onto an A4
    [
        1 => true,
        2 => true,
        3 => true,
        4 => true,
    ]
);

if(is_array($label))
{
	//print $label[0];
	print "PostNL heeft deze zending niet geaccepteerd. Controleer de adresgegevens.";
}
else
{
	header("Content-type:application/pdf");
	header("Content-Disposition:attachment;filename='label.pdf'");
	print $label;
}
?>
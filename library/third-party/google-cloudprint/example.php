<?php
session_start();
error_reporting(0);
 
$actual_link = "https://$_SERVER[HTTP_HOST]";

define("_LANGUAGE_PACK", "nl");

require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/classes/motherboard.php");

$mb = new motherboard();
$settings = $mb->_runFunction("pos", "loadPrinterSettings", array($_SESSION['merchantID']));

if(count($settings) == 0)
{
	die("Error while loading settings.");
}



require_once 'GoogleCloudPrint.php';

session_start();

$gcp = new GoogleCloudPrint();
$gcp->setAuthToken($_SESSION['accessToken']);

$printers = $gcp->getPrinters();

$printerid = "";

if(count($printers) > 0) 
{
	foreach($printers AS $key => $value)
	{
		if($printers[$key]['id'] == $settings['google_cloud_printer_id'])
		{
			$type = substr($_SESSION['print_file'], -3);
			
			if($type == "pdf")
			{
				$type = "application/pdf";
			}
			else
			{
				$type = "text/html";
			}
			
			$resarray = $gcp->sendPrintToPrinter(
				$printers[$key]['id'],
				"Printing file " . $_SESSION['print_file'],
				$_SESSION['print_file'],
				$type
			);
			
		}
	}
	
	unset($_SESSION['print_file']);
}
?>
<script type="text/javascript">
	setTimeout(
		function()
		{
			window.close();
		},
		250
	);
</script>
<?php	
try
{
	// iDeal keys. 25011991 and 005087517.
	$key = $_api_key_1;
	$merchantID = $_api_key_2;
	
	// Succes and error URL
	$urlSuccess = "https://merchant.justinharings.nl/extensions/payments/process.php?orderID=" . $orderID;
	$urlCancel = $_cancel_url;
	$urlError = $_cancel_url;
	
	// Order ID
	$purchaseID = $orderID;
	
	// Calculate the total
	$grand_total = $this->calcTotal($orderID);
	
	$expl = explode(".", $grand_total);
	$heel = $expl[0] * 100;
	$cent = $expl[1];
	
	$grand_total = $heel + $cent;
	$amount = $itemPrice1 = $grand_total;
	
	// Data shown at the iDeal screen
	$description = "Betaling voor order #" . $orderID;
	
	// Other iDeal information
	$subID = "0";
	$paymentType = "ideal";
	$validUntil = date('Y-m-d\TH:i:s.000\Z', time()+900);
	$itemNumber1 = "1";
	$itemDescription1 = "omschrijving";
	$itemQuantity1 = 1;
	
	$shastring = "$key$merchantID$subID$amount$purchaseID$paymentType$validUntil"
	. "$itemNumber1$itemDescription1$itemQuantity1$itemPrice1";
	
	$shastring = preg_replace(
		array("/[ \t\n]/", '/&amp;/i', '/&lt;/i', '/&gt;/i', '/&quot/i'),
		array( '', '&', '<', '>', '"'),
		$shastring
	);
	
	$shasign = sha1($shastring);
	
	$language = '';
	$currency = 'EUR';
	
	
	// Require the initialize function from Mollie.
	require_once("/var/www/vhosts/justinharings.nl/merchant.justinharings.nl/library/third-party/payment-modules/systems/mollie/database.php");
	
	// Store data that can be used later on.
	$data = array();
	$data[0] = 0;
	$data[1] = $orderID;
	$data[2] = $this->calcTotal($orderID);
	$data[3] = $_api_key_1;
	$data[4] = $_api_key_2;
	$data[5] = (isset($_GET['language_pack']) ? $_GET['language_pack'] : "");

	database_write($orderID, serialize($data), _DEVELOPMENT_ENVIRONMENT);
	
	
echo <<<EOT
<form method="post" action="https://ideal.secure-ing.com/ideal/mpiPayInitIng.do" id="idealform" name="idealform">
<!-- Vergeet na het uitvoeren van de testen niet de url in de ACTION te veranderen naar de productie-omgeving -->
<input type="hidden" name="merchantID" value="$merchantID">
<!-- voorbeeld met POST variabele:
<input type="hidden" name="merchantID" value="{$merchantID}"> Let op: Altijd POST/GET verifieren c.q. opschonen voor gebruik. Hiermee kan ongecontroleerd extra html code geinjecteerd worden. (bv javascript dit auto-submit doet)-->
<input type="hidden" name="subID" value="$subID">
<input type="hidden" name="amount" value="$amount">
<input type="hidden" name="purchaseID" value="$purchaseID">
<input type="hidden" name="language" value="$language">
<input type="hidden" name="currency" value="$currency">
<input type="hidden" name="description" value="$description">
<input type="hidden" name="hash" value="$shasign">
<input type="hidden" name="paymentType" value="$paymentType">
<input type="hidden" name="validUntil" value="$validUntil">
<input type="hidden" name="itemNumber1" value="$itemNumber1">
<input type="hidden" name="itemDescription1" value="$itemDescription1">
<input type="hidden" name="itemQuantity1" value="$itemQuantity1">
<input type="hidden" name="itemPrice1" value="$itemPrice1">
<input type="hidden" name="urlSuccess" value="$urlSuccess">
<input type="hidden" name="urlCancel" value="$urlCancel">
<input type="hidden" name="urlError" value="$urlError">
</form>
EOT;
	
	print '<script type="text/javascript">';
	print 	"document.idealform.submit();";
	print '</script>';
}
catch (Exception $e)
{
	// Something failed, go back to the cancel page.
	header("location: " . $_cancel_url);
	//print $e->getMessage();
}
?>
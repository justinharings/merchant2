<?php
if(!isset($_SESSION))
{
	session_start();
}

if	(
		!isset($_GET['type'])
		|| !isset($_GET['action'])
		|| 	(
				!isset($_GET['orderID'])
				&& !isset($_GET['workorderID'])
			)
	)
{
	die("Request made without the right information.");
}



/*
**	Find the order provided. If we can not
**	find the order, the print is useless.
*/

define("_LANGUAGE_PACK", "NL");

// Get the DEV or LIVE environment.
$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$dev = false;

if(strpos($actual_link, "dev.justin") !== false)
{
	$dev = true;
	define("_DEVELOPMENT_ENVIRONMENT", $dev);
}

require_once("/var/www/vhosts/justinharings.nl/" . (_DEVELOPMENT_ENVIRONMENT ? "dev" : "merchant") . ".justinharings.nl/library/php/functions/arrays.php");
require_once("/var/www/vhosts/justinharings.nl/" . (_DEVELOPMENT_ENVIRONMENT ? "dev" : "merchant") . ".justinharings.nl/library/php/functions/floats.php");
require_once("/var/www/vhosts/justinharings.nl/" . (_DEVELOPMENT_ENVIRONMENT ? "dev" : "merchant") . ".justinharings.nl/library/php/functions/text.php");

require_once("/var/www/vhosts/justinharings.nl/" . (_DEVELOPMENT_ENVIRONMENT ? "dev" : "merchant") . ".justinharings.nl/library/php/classes/motherboard.php");

$mb = new motherboard();

$order = $mb->_runFunction("orders", "load", array($_GET['orderID']));
$workorder = $mb->_runFunction("workorders", "loadWorkorder", array($_GET['workorderID']));

if(!$order['orderID'] && !$workorder['workorderID'])
{
	die("Order not found.");
}
else
{
	$merchant = $mb->_runFunction("merchant", "load", array(($order['merchantID'] ? $order['merchantID'] : $workorder['merchantID'])));
	$invoice = $mb->_runFunction("cms", "loadInvoiceText", array($order['merchantID']));
	$customer = $mb->_runFunction("customers", "load", array($order['customerID']));
	$workorder_receipt = $mb->_runFunction("workorders", "loadSettings", array($_SESSION['merchantID']));
	
	$template_suffix = "";
	
	switch($customer['country'])
	{
		default:
			$template_suffix = "en";
			
			$invoice['invoice_text'] = $invoice['EN_invoice_text'];
			$invoice['invoice_extra'] = $invoice['EN_invoice_extra'];
			$invoice['receipt_text'] = $invoice['EN_receipt_text'];
		break;
		
		case "":
		case "Netherlands":
		case "Belgium":
			$template_suffix = "";
		break;
		
		case "Germany":
			$template_suffix = "de";
			
			$invoice['invoice_text'] = $invoice['DE_invoice_text'];
			$invoice['invoice_extra'] = $invoice['DE_invoice_extra'];
			$invoice['receipt_text'] = $invoice['DE_receipt_text'];
		break;
	}
}



/*
**	Create the content. We use special made TAGS and a
**	HTML template file for content creation.
*/

$file = __DIR__ . "/templates/" . $_GET['type'] . ($template_suffix != "" ? "_" . $template_suffix : "") . ".html";

if(file_exists($file))
{
	$content = file_get_contents($file);
	
	$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
	
	// CSS Replacement
	$css .= file_get_contents("/var/www/vhosts/justinharings.nl/" . (_DEVELOPMENT_ENVIRONMENT ? "dev" : "merchant") . ".justinharings.nl/library/css/normalize.css");
	$css = file_get_contents("/var/www/vhosts/justinharings.nl/" . (_DEVELOPMENT_ENVIRONMENT ? "dev" : "merchant") . ".justinharings.nl/library/css/printer.css");
	$content = str_replace("[[css]]", $css, $content);
	
	// Merchant replacement
	$content = str_replace("[[store_name]]", $merchant['company_name'], $content);
	$content = str_replace("[[invoice_company_info]]", nl2br($invoice['invoice_text']), $content);
	$content = str_replace("[[invoice_comments]]", nl2br($invoice['invoice_extra']), $content);
	$content = str_replace("[[receipt_company_info]]", nl2br($invoice['receipt_text']), $content);
	$content = str_replace("[[logo_merchant]]", '<img class="logo" src="' . $actual_link . '/library/media/merchant_logos/' . $merchant['merchantID'] . '.png" />', $content);
	
	// Workorder replacement
	$content = str_replace("[[workorder_receipt]]", nl2br($workorder_receipt['receipt_content']), $content);
	$content = str_replace("[workorder-ID]", $workorder['workorderID'], $content);
	$content = str_replace("[[workorder-ID]]", $workorder['workorderID'], $content);
	$content = str_replace("[workorder-PHONENUMBER]", ($workorder['phone_number'] ? $workorder['phone_number'] : "Onbekend"), $content);
	$content = str_replace("[[workorder-PHONENUMBER]]", ($workorder['phone_number'] ? $workorder['phone_number'] : "Onbekend"), $content);
	$content = str_replace("[workorder-KEYNUMBER]", $workorder['key_number'], $content);
	$content = str_replace("[[workorder-KEYNUMBER]]", $workorder['key_number'], $content);
	$content = str_replace("[workorder-DATE]", $workorder['expiration_date'], $content);
	$content = str_replace("[[workorder-DATE]]", $workorder['expiration_date'], $content);
	$content = str_replace("[workorder-BARCODE]", '<img src="' . $actual_link . '/library/third-party/barcode-image/barcode.php?code=' . $workorder['workorderID'] . '" />', $content);
	$content = str_replace("[[workorder-BARCODE]]", '<img src="' . $actual_link . '/library/third-party/barcode-image/barcode.php?code=' . $workorder['workorderID'] . '" />', $content);
	
	// Customer replacement
	$content = str_replace("[[customer_name]]", $customer['name'], $content);
	$content = str_replace("[[second_name]]", $customer['company'], $content);
	$content = str_replace("[[customer_address]]", $customer['address'], $content);
	$content = str_replace("[[customer_postcode]]", $customer['zip_code'], $content);
	$content = str_replace("[[customer_city]]", $customer['city'], $content);
	$content = str_replace("[[customer_phone]]", ($customer['mobile_phone'] ? $customer['mobile_phone'] : $customer['phone']), $content);
	
	// Order replacement
	$content = str_replace("[[orderID]]", $order['order_reference'], $content);
	$content = str_replace("[[orderDate]]", $order['date_added'], $content);
	$content = str_replace("[[barcode]]", '<img src="' . $actual_link . '/library/third-party/barcode-image/barcode.php?code=' . $order['orderID'] . '" />', $content);
	$content = str_replace("[[shipping]]", $order['date_added'], $content);
	$content = str_replace("[[total]]", _frontend_float($order['grand_total']), $content);
	$content = str_replace("[[total_taxes]]", _frontend_float($order['vat_total']), $content);
	
	$shipment_costs = 0;
	
	foreach($order['shipments'] AS $shipment)
	{
		$shipment_costs += $shipment['price'];
	}
	
	$content = str_replace("[[shipping_costs]]", _frontend_float($shipment_costs), $content);
	$content = str_replace("[[subtotal]]", _frontend_float($order['grand_total']-$shipment_costs), $content);
	
	
	$product_row = "";
	$articles_count = 0;
	
	foreach($order['products'] AS $product)
	{
		$articles_count += $product['quantity'];
		$font_size = (count($order['products']) > 20 ? "font-size: 10px;" : "");
		
		if($template_suffix != "" && $product[strtoupper($template_suffix) . '_name'] != "")
		{
			$product['name'] = $product[strtoupper($template_suffix) . '_name'];
		}
		
		if($template_suffix != "" && $product[strtoupper($template_suffix) . '_price'] != "")
		{
			$product['price'] = $product[strtoupper($template_suffix) . '_price'];
		}
		
		$product_row .= '
			<tr>
				<td style="padding: 5px 0px 5px 10px; ' . $font_size . '">
					'. ($_GET['type'] == "picklist" ? "o&nbsp;o&nbsp;&nbsp;" . _chopString($product['name'], 40) : $product['name']) .'
				</td>
				<td style="padding: 5px 0px; ' . $font_size . '">'. $product['quantity'] .' stuk(s)</td>
				<td style="padding: 5px 0px; ' . $font_size . '">&euro;&nbsp;'. _frontend_float($product['price']) .'</td>
				<td style="padding: 5px 0px; ' . $font_size . '">&euro;&nbsp;'. _frontend_float($product['quantity']*$product['price']) .'</td>
			</tr>
		';
	}
	
	$content = str_replace("[[products_rows]]", $product_row, $content);
	$content = str_replace("[[total_articles]]", $articles_count, $content);
	
	
	$receipt_row = "";
	$articles_count = 0;
	
	foreach($order['products'] AS $product)
	{
		$articles_count += $product['quantity'];
		$font_size = (count($order['products']) > 20 ? "font-size: 10px;" : "");
		
		$receipt_row .= '
			<tr>
				<td style="font-weight: bold; ' . $font_size . '">' . $product['quantity'] . 'x</td>
				<td style="font-weight: bold; ' . $font_size . '">' . $product['name'] . '</td>
			</tr>
			
			<tr>
				<td colspan="2" style="padding: 0px 0px 10px 0px; ' . $font_size . '">EUR&nbsp;&nbsp;' . _frontend_float($product['price']) . '</td>
			</tr>
		';
	}
	
	$content = str_replace("[[receipt_row]]", $receipt_row, $content);
	
	
	
	$payments_row = "";
	$articles_count = 0;
	
	foreach($order['payments'] AS $payment)
	{
		$payments_row .= '
			<tr>
				<td>' . $payment['date'] . '</td>
				<td>&euro;&nbsp;' . _frontend_float($payment['amount']) . '</td>
			</tr>
		';
	}
	
	$content = str_replace("[[orderPayments]]", $payments_row, $content);
	
	
	$extra = "";
	
	for($i = 0; $i <= 4; $i++)
	{
		if($order['invoice_rules'][$i-1]['key'])
		{
			$extra .= '
				<tr>
					<td class="padding-full line-top">'. $order['invoice_rules'][$i-1]['key'] .':</td>
					<td class="padding-full line-top">'. $order['invoice_rules'][$i-1]['value'] .'</td>
				</tr>
			';
		}
	}
	
	if($extra != "")
	{
		$extra = '
		<table class="margin-top">
			<tr>
				<td width="50%">
					<table class="margin-top-double border">
						<tr>
							<td colspan="2" class="background" style="background: #000;">Extra informatie</td>
						</tr>
						
						' . $extra . '
					</table>
				</td>
				
				<td>&nbsp;</td>
			</tr>
		</table>';
	}
	
	$content = str_replace("[[extra_invoice_data]]", $extra, $content);
}
else
{
	die("Type of file does not exists. " . $file);
}


/*
**	If it's a receipt, we're not using the normal
**	HTML2PDF and Google Cloudprint services.
*/

if($_GET['type'] == "receipt" || $_GET['type'] == "workorder")
{
	print $content;
	
	?>
	<script type="text/javascript">
		window.print();
		setTimeout(function() { window.close(); }, 1000);
	</script>
	<?php
}
else if($_GET['type'] != "receipt" && $_GET['type'] != "workorder")
{
	/*
	**	Create a PDF file from the output.
	**	This file is stored in a temporary folder
	**	in order to load it and use it.
	*/
	
	try
	{
		require_once("/var/www/vhosts/justinharings.nl/" . (_DEVELOPMENT_ENVIRONMENT ? "dev" : "merchant") . ".justinharings.nl/library/third-party/html2pdf/html2pdf.class.php");
		
		$file = 'print_' . rand() . '.pdf';
		
	    $html2pdf = new HTML2PDF('P', 'A4', 'en', true, 'UTF-8', array(10, 10, 10, 10));
	    $html2pdf->pdf->SetDisplayMode('fullpage');
	    $html2pdf->setDefaultFont("montserrat");
	    $html2pdf->writeHTML($content);
	    $html2pdf->Output("/var/www/vhosts/justinharings.nl/" . (_DEVELOPMENT_ENVIRONMENT ? "dev" : "merchant") . ".justinharings.nl/temp/" . $file, 'F');
	}
	catch(HTML2PDF_exception $e) 
	{
	    echo $e;
	    exit;
	}
	
	
	
	/*
	**	Now let's process what to do with the request.
	**	Print it with Google Cloud Print or just save it?
	*/
	
	//print $content;
	
	if($_GET['action'] == "print")
	{
		//print $content;
		$url = "/library/third-party/google-cloudprint/index.php?print_file=/var/www/vhosts/justinharings.nl/" . (_DEVELOPMENT_ENVIRONMENT ? "dev" : "merchant") . ".justinharings.nl/temp/" . $file;
		header("location: " . $url);
	}
	else if($_GET['action'] == "save")
	{
		$_file_name = "/var/www/vhosts/justinharings.nl/" . (_DEVELOPMENT_ENVIRONMENT ? "dev" : "merchant") . ".justinharings.nl/temp/" . $file;
	}
}
?>
<?php
// Start session

if(!isset($_SESSION))
{
	session_start();
}

define("_LANGUAGE_PACK", "nl");

/*
**	Functions are added here. Used for quick access to all
**	of the extended special functions, all the files
**	are added to the core here.
*/

require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/functions/arrays.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/functions/floats.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/functions/text.php");



/*
**	Classes are included here. We use a motherboard
**	class that is able to construct all the classes
**	and is able to run this class his function.
*/

require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/classes/motherboard.php");

$mb = new motherboard();

if(isset($_GET['key']))
{
	$data = $mb->_runFunction("workorders", "loadWorkorder", array($_GET['key']));
}

$data_workorder = $mb->_runFunction("workorders", "loadSettings", array($_SESSION['merchantID']));
?>

<!DOCTYPE html>
<html lang="nl">
	<head>
		<link rel="stylesheet" type="text/css" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" />
		<link rel="stylesheet" type="text/css" href="/library/css/motherboard.minified.css" />
		<link rel="stylesheet" type="text/css" href="/library/css/pos.css" />

		<script type="text/javascript" src="//code.jquery.com/jquery-latest.js"></script>
		<script type="text/javascript" src="/library/js/motherboard.minified.js"></script>
		<script type="text/javascript" src="/library/js/pos.js"></script>
	</head>

	<body class="popup">
		<form id="workorder-form" method="post" action="/extensions/point_of_sale/library/php/posts/workorder_add.php">
			<input type="hidden" name="priority" id="priority" value="<?= isset($_GET['key']) && $data['priority'] ? "1" : "0" ?>" />
			<input type="hidden" name="workorderID" id="workorderID" value="<?= isset($_GET['key']) ? $_GET['key'] : 0 ?>" />
			<input type="hidden" name="customerID" id="customerID" value="<?= isset($_GET['key']) && $data['customerID'] ? $data['customerID'] : 0 ?>" />
			<input type="hidden" name="workorder_unique_code" id="workorder_unique_code" value="<?= $data_workorder['unique_identifier'] ?>" />
			<input type="hidden" name="key_number_current" id="key_number_current" value="<?= isset($_GET['key']) ? $data['key_number'] : "" ?>" />
			
			<div style="width: 210px; position: relative; padding: 0px 10px 0px 0px; float: left; border-right: 1px solid #ccc;">
				<div class="priority" style="width: 34px; height: 34px; position: absolute; top: 166px; right: 60px; z-index: 995; border: 1px solid #ddd; border-radius: 7px;"><span style="margin: 9px 0px 0px 9px; font-size: 17px;" class="fa fa-exclamation-triangle <?= isset($_GET['key']) && $data['priority'] ? "red" : "" ?>"></span></div>
				
				<input <?= isset($_GET['key']) && $data['customerID'] > 0 ? "disabled=\"disabled\"" : "" ?> type="text" name="customer_code" id="customer_code" value="<?= isset($_GET['key']) && $data['customerID'] > 0 ? $data['customerID'] : "" ?>" class="width-100-percent double-margin" holder="Klanten code" icon="fa-barcode" />
				
				<input type="text" name="phone_number" id="phone_number" value="<?= isset($_GET['key']) ? $data['phone_number'] : "" ?>" class="width-100-percent double-margin" holder="Mobiele nummer" icon="fa-mobile-phone" />
				
				<input type="text" name="expiration_date" id="expiration_date" value="<?= isset($_GET['key']) ? $data['expiration_date'] : "" ?>" class="width-100 margin datepicker" holder="Verloopdatum" icon="fa-calendar" validation-required="true" validation-type="text" />
				<input type="text" name="key_number" id="key_number" value="<?= isset($_GET['key']) ? $data['key_number'] : "" ?>" class="width-100 double-margin" holder="Sleutelnummer" icon="fa-key" validation-required="true" validation-type="text" />
				
				<select multiple="multiple" name="status" id="status" style="height: 73px;" class="width-100-percent no-multiselect margin" holder="Status">
					<option <?= !isset($_GET['key']) || (isset($_GET['key']) && $data['status'] == 0) ? "selected=\"selected\"" : "" ?> value="0">Openstaand</option>
					<option <?= (isset($_GET['key']) && $data['status'] == 1) ? "selected=\"selected\"" : "" ?> value="1">Afgerond</option>
					<option <?= (isset($_GET['key']) && $data['status'] == 2) ? "selected=\"selected\"" : "" ?> value="2">In de wacht</option>
				</select>
				
				<input type="submit" name="save" id="save" value="Werkbon opslaan" class="width-100-percent red margin show-load validate-form" />
			</div>
			
			<div style="width: 369px; padding: 0px 0px 0px 10px; float: right;">
				<textarea name="workorder" id="workorder" class="width-100-percent margin" holder="Reparatie" style="height: 144px;" validation-required="true" validation-type="text"><?= isset($_GET['key']) ? $data['workorder'] : "" ?></textarea>
				<textarea name="note" id="note" class="width-100-percent margin" holder="Opmerkingen" style="height: 120px;"><?= isset($_GET['key']) ? $data['note'] : "" ?></textarea>
				
				<div style="width: calc(33% - 8px); height: 40px; margin: 0px 10px 0px 0px; padding: 12px 0px 0px 0px; float: left; border: 1px solid #ddd; border-radius: 7px; text-align: center;">
					SMS<br/>
					<?= isset($_GET['key']) ? ($data['sms_sent'] ? "Ja" : "Nee") : "-" ?>
				</div>
				
				<div style="width: calc(33% - 8px); height: 40px; margin: 0px 10px 0px 0px; padding: 12px 0px 0px 0px; float: left; border: 1px solid #ddd; border-radius: 7px; text-align: center;">
					TOTAAL<br/>
					<?= isset($_GET['key']) ? "&euro;&nbsp;" . _frontend_float($data['grand_total']) : "-" ?>
				</div>
				
				<div style="width: calc(33% - 8px); height: 40px; padding: 12px 0px 0px 0px; float: left; border: 1px solid #ddd; border-radius: 7px; text-align: center;">
					MONTEUR<br/>
					<?= isset($_GET['key']) ? ($data['mechanic'] ? $data['mechanic'] : "-") : "-" ?>
				</div>
			</div>
		</form>
		
		<script type="text/javascript">
			$(document).ready(
				function($)
				{
					$(".priority").on("click",
						function()
						{
							if($(this).find("span").hasClass("red"))
							{
								$(this).find("span").removeClass("red");
								$("input#priority").val(0);
							}
							else
							{
								$(this).find("span").addClass("red");
								$("input#priority").val(1);
							}
						}
					);
					
					$(document).on("keypress", 'input',
						function(e)
						{
							var code = e.keyCode || e.which;
							
							if($(this).attr("id") == "customer_code" && code == 13) 
							{
								e.preventDefault();
								
								var elm = $(this);
								
								elm
									.prev("span")
									.removeClass("fa-barcode")
									.addClass("fa-circle-o-notch")
									.addClass("fa-spin");
									
								$("input#phone_number")
									.val("")
									.prev("span")
									.addClass("fa-mobile-phone")
									.removeClass("fa-check");
									
								$("input#customerID").val(0);
									
								$.post(
									"/extensions/point_of_sale/library/php/posts/search_workorders_customer.php",
									{
										search: elm.val()
									}
								).done(
									function(data) 
									{
										data = $.parseJSON(data);
										
										elm.prev("span")
											.removeClass("fa-circle-o-notch")
											.removeClass("fa-spin")
											.removeClass("fa-times");
										
										try
										{
											$("input#customerID").val(data['customerID']);
											elm.prev("span").addClass("fa-check");
										}
										catch(err)
										{ }
										
										try
										{
											if(data['mobile_phone'] != "")
											{
												$("input#phone_number")
													.val(data['mobile_phone'])
													.prev("span")
													.removeClass("fa-mobile-phone")
													.addClass("fa-check");
												
												elm.prev("span").addClass("fa-check");
											}
										}
										catch(err)
										{
											elm.prev("span").addClass("fa-times");
										}
									}
								);
								
								return false;
							}
						}
					);
				}
			);
		</script>
	</body>
</html>	
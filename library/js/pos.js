$(document).ready(
	function($)
	{
		/*
		**	General javascript settings and
		**	more-used functions.
		*/
		
		$("input").each(
			function()
			{
				$(this).attr("autocomplete", "off");
			}
		);
		
		setTimeout(
			function()
			{
				$("input#search").focus();
			}, 200
		);
		
		$("*[click]").on("click",
			function()
			{
				document.location.href = $(this).attr("click");
			}
		);
		
		$(".menu-item[popup]").on("click",
			function()
			{
				var url = $(this).attr("popup");
				popup(800, 600, url);
			}
		);
		
		function getKey()
		{
			return $("tr.active").attr("key");
		}
		
		function selectAndScrollToOption(select, option) 
		{
			$select = $(select);
	
			var $selectedOptions = $select.find("option:selected");

			option.selected = true; // Required for old IE
			select.selectedIndex = option.index;

			var scrollTop = $select.scrollTop();

			$selectedOptions.prop("selected", true);

			$select.scrollTop(scrollTop);
		}
		
		$("span.print-last").on("click",
			function()
			{
				if($(this).attr("target") == "workorder" && $(this).attr("workorderID") != "")
				{
					window.open('/extensions/printserver/index.php?type=workorder&action=print&workorderID=' + $(this).attr("targetID"));
				}
				else if($(this).attr("target") == "receipt" && $(this).attr("workorderID") != "")
				{
					window.open('/extensions/printserver/index.php?type=receipt&action=print&orderID=' + $(this).attr("targetID"));
				}
				
				$(this).fadeOut("fast");
				$(".print-last-circle").fadeOut("fast");
			}
		);
		
		setTimeout(
			function()
			{
				$(".print-last").fadeOut("fast");
				$(".print-last-circle").fadeOut("fast");
			}, 30000
		);
		
		
		
		/*
		**	Menu items without a normal
		**	relation for next page.
		*/
		
		$(".close-register").on("click",
			function()
			{
				popup(300, 80, "/extensions/point_of_sale/modules/popup_close_register.php");
			}
		);
		
		$(".open-drawer").on("click",
			function()
			{
				window.open("/extensions/point_of_sale/modules/open_drawer.php");
			}
		);
		
		
		
		/*
		**	Barcode search, adding and focus
		*/
		
		setInterval(
			function()
			{
				if($("div.popup-overlay").css("display") == "none")
				{
					var elm = $("input#barcode");
					elm.focus();
				}
			}, 100
		);
		
		$("div.keyboard").on("click",
			function()
			{
				var value = $("input#barcode").val();
				
				if($(this).hasClass("fa-backward"))
				{
					value = value.slice(0, -1);
					
					$("input#barcode").val(value);
					
					return false;
				}
				
				var value = $("input#barcode").val();
				value = value + $(this).html();
				
				$("input#barcode").val(value);
			}
		);
		
		$(document).on("keypress", 'form',
			function(e)
			{
				var code = e.keyCode || e.which;
				
				if($(this).attr("id") == "barcode-form" && code == 13) 
				{
					$(this).find("input").attr("readonly", "readonly");
					
					e.preventDefault();
					$(this).submit();
					
					return false;
				}
			}
		);
		
		
		
		/*
		**	Keyboard functions for register screen.
		*/
		
		$("#new_order").on("click",
			function()
			{
				$("div.loader").show();
				document.location.href = '/extensions/point_of_sale/library/php/posts/cart_reset.php';
			}
		);
		
		$("div.register-keyboard").find(".fa-plane").on("click",
			function()
			{
				if($("tr.active").length > 0)
				{
					popup(370, 210, "/extensions/point_of_sale/modules/popup_shipment.php");
				}
			}
		);
		
		$("div.register-keyboard").find(".fa-paper-plane").on("click",
			function()
			{
				if($("tr.active").length > 0)
				{
					var key = getKey();
					
					if($(this).attr("cart") == 0)
					{
						popup(318, 363, "/extensions/point_of_sale/modules/popup_terminal.php?orderID=" + key);
					}
				}
			}
		);
		
		$("div.register-keyboard").find(".fa-hashtag").on("click",
			function()
			{
				if($("tr.active").length > 0)
				{
					var span = $("input#barcode").prev("span");
					var qty = $("input#qty");
					var key = $("input#key");
					
					if(span.hasClass("fa-barcode"))
					{
						qty.val(1);
						key.val(getKey());
						
						span.toggleClass("fa-barcode fa-hashtag");
					}
					else
					{
						qty.val(0);
						key.val(0);
						
						span.toggleClass("fa-hashtag fa-barcode");
					}
				}
			}
		);
		
		$("div.register-keyboard").find(".fa-trash").on("click",
			function()
			{
				if($("tr.active").length > 0)
				{
					$("div.loader").show();
					
					var key = getKey();
					
					document.location.href = '/extensions/point_of_sale/library/php/posts/cart_remove.php?key=' + key;
				}
			}
		);
		
		$("div.register-keyboard").find(".fa-euro").on("click",
			function()
			{
				if($("tr.active").length > 0)
				{
					var key = getKey();
					popup(318, 363, "/extensions/point_of_sale/modules/popup_price.php?key=" + key);
				}
			}
		);
		
		$("div.register-keyboard").find(".fa-pencil").on("click",
			function()
			{
				if($("tr.active").length > 0)
				{
					var key = getKey();
					popup(797, 440, "/extensions/point_of_sale/modules/popup_name.php?key=" + key);
				}
			}
		);
		
		$("div.register-keyboard").find(".fa-list").on("click",
			function()
			{
				if($("tr.active").length > 0)
				{
					var key = getKey();
					popup(520, 250, "/extensions/point_of_sale/modules/popup_invoice_rules.php");
				}
			}
		);
		
		$("div.register-keyboard").find(".fa-history").on("click",
			function()
			{
				if($("tr.active").length > 0)
				{
					document.location.href = '/extensions/point_of_sale/library/php/posts/parking.php';
				}
			}
		);
		
		$("div.register-keyboard").find(".fa-print").on("click",
			function()
			{
				if($(this).parent().attr("orderID") > 0)
				{
					window.open('/extensions/printserver/index.php?type=receipt&action=print&orderID=' + $(this).parent().attr("orderID"));
				}
			}
		);
		
		$("div.register-keyboard").find(".fa-cart-arrow-down").on("click",
			function()
			{
				if($("input#barcode").val() != "")
				{
					$("div.loader").show();
					$("#barcode-form").submit();
				}
			}
		);
		
		$("input#run_order").on("click",
			function()
			{
				if($("tr.active").length > 0)
				{
					popup(500, 180, '/extensions/point_of_sale/modules/popup_start_payment.php');
				}
			}
		);
		
		
		
		/*
		**	Start process order
		*/
		
		$(".payment-method").on("click",
			function()
			{
				if($(this).hasClass("inactive"))
				{
					return false;
				}
				
				if($(this).attr("paymentID") == 0)
				{
					popup(370, 210, "/extensions/point_of_sale/modules/popup_status.php");
					return false;
				}
				
				popup(318, 460, '/extensions/point_of_sale/modules/popup_payment_amount.php?target=' + $(this).attr("target") + '&grand_total=' + $("input#grand_total", parent.document).val() + '&paymentID=' + $(this).attr("paymentID"));
			}
		);
		
		$(".order-finished[target='eject']").on("click",
			function()
			{
				$(".fa-eject", parent.document).trigger("click");
			}
		);
		
		$(".order-finished[target='finish']").on("click",
			function()
			{
				document.location.href = '/extensions/point_of_sale/library/php/posts/finish.php';
			}
		);
		
		if($(".order-finished").length > 0)
		{
			$("div.popup-container", parent.document).find("div.closer").hide();
		}
		
		
		
		/*
		**	Buttons in views.
		*/
		
		$("#use_customer").on("click",
			function()
			{
				var key = getKey();
				
				if(key)
				{
					$("div.loader").show();
					document.location.href = '/extensions/point_of_sale/library/php/posts/cart_customer_add.php?key=' + key;
				}
			}
		);
		
		$("#use_product").on("click",
			function()
			{
				var key = getKey();
				
				if(key)
				{
					$("div.loader").show();
					document.location.href = '/extensions/point_of_sale/library/php/posts/cart_add.php?barcode=' + key;
				}
			}
		);
		
		$("#scan_barcode").on("click",
			function()
			{
				var key = getKey();
				
				if(key)
				{
					popup(400, 135, "/extensions/point_of_sale/modules/popup_barcode.php?key=" + key);
				}
			}
		);
		
		$("#use_parked").on("click",
			function()
			{
				var key = getKey();
				
				if(key)
				{
					$("div.loader").show();
					document.location.href = '/extensions/point_of_sale/library/php/posts/parking_load.php?parkingID=' + key;
				}
			}
		);
		
		$("#print_invoice").on("click",
			function()
			{
				var key = getKey();
				
				if(key)
				{
					window.open('/extensions/printserver/index.php?type=invoice&action=print&orderID=' + key);
				}
			}
		);
		
		$("#print_tender").on("click",
			function()
			{
				var key = getKey();
				
				if(key)
				{
					window.open('/extensions/printserver/index.php?type=tender&action=print&orderID=' + key);
				}
			}
		);
		
		$("#print_picklist").on("click",
			function()
			{
				var key = getKey();
				
				if(key)
				{
					window.open('/extensions/printserver/index.php?type=picklist&action=print&orderID=' + key);
				}
			}
		);
		
		$("#print_receipt").on("click",
			function()
			{
				var key = getKey();
				
				if(key)
				{
					window.open('/extensions/printserver/index.php?type=receipt&action=print&orderID=' + key);
				}
			}
		);
		
		$("#new_customer").on("click",
			function()
			{
				popup(301, 635, "/extensions/point_of_sale/modules/popup_customer.php");
			}
		);
		
		$("#edit_customer").on("click",
			function()
			{
				var key = getKey();
				
				if(key)
				{
					popup(301, 635, "/extensions/point_of_sale/modules/popup_customer.php?key=" + key);
				}
			}
		);
		
		$("#scan_customer_card").on("click",
			function()
			{
				var key = getKey();
				
				if(key)
				{
					popup(400, 135, "/extensions/point_of_sale/modules/popup_customer_card.php?key=" + key);
				}
			}
		);
		
		$("#new_workorder").on("click",
			function()
			{
				popup(600, 420, "/extensions/point_of_sale/modules/popup_workorder.php");
			}
		);
		
		$("#edit_workorder").on("click",
			function()
			{
				var key = getKey();
				
				if(key)
				{
					popup(600, 420, "/extensions/point_of_sale/modules/popup_workorder.php?key=" + key);
				}
			}
		);
		
		$("#workshop").on("click",
			function()
			{
				document.location.href = '/workshop/modules/open/';
			}
		);
		
		$("#load_workorder").on("click",
			function()
			{
				var key = getKey();
				var msg = 'Weet u zeker dat u deze werkorder wilt inladen?';
				
				if(key && confirm(msg))
				{
					document.location.href = '/extensions/point_of_sale/library/php/posts/workorder_load.php?workorderID=' + key;
				}
			}
		);
		
		$("#open_documentation").on("click",
			function()
			{
				var key = getKey();
				
				if(key)
				{
					popup(600, 420, "/extensions/workshop/modules/popup_documentation_view.php?key=" + key);
				}
			}
		);
		
		$("#new_documentation").on("click",
			function()
			{
				popup(600, 295, "/extensions/workshop/modules/popup_documentation_form.php");
			}
		);
		
		$("#edit_documentation").on("click",
			function()
			{
				var key = getKey();
				
				if(key)
				{
					popup(600, 295, "/extensions/workshop/modules/popup_documentation_form.php?key=" + key);
				}
			}
		);
		
		
		
		/*
		**	Workorders
		*/
		
		$(".workshop.battery-test").on("click",
			function()
			{
				popup(300, 135, "/extensions/workshop/modules/popup_battery_detection.php");
			}
		);
		
		$(".fa-music").on("click",
			function()
			{
				window.open('http://www.nederland.fm', 'radio', true);
			}
		);
		
		$(".workorder-action.fa-check, .workorder-action.fa-comments").on("click",
			function()
			{
				$(this).removeClass("fa-check").addClass("fa-circle-o-notch").addClass("fa-spin");
				
				var workorderID = $(this).attr("workorderID");
				var phone_number = $(this).attr("phone_number");
				document.location.href = "/extensions/workshop/library/php/posts/finish.php?workorderID=" + workorderID + "&phone_number=" + phone_number;
			}
		);
		
		$(".workorder-action.fa-pencil").on("click",
			function()
			{
				var workorderID = $(this).attr("workorderID");
				
				popup(440, 510, "/extensions/workshop/modules/popup_card.php?workorderID=" + workorderID);
			}
		);
		
		$(".workorder-action.fa-reply").on("click",
			function()
			{
				$(this).removeClass("fa-clock-o").addClass("fa-circle-o-notch").addClass("fa-spin");
				
				var returnOpen = "";
				
				if($(this).hasClass("return-closed"))
				{
					returnOpen = "&return_closed=true";
				}
				
				var workorderID = $(this).attr("workorderID");
				document.location.href = "/extensions/workshop/library/php/posts/reopen.php?workorderID=" + workorderID + returnOpen;
			}
		);
		
		$(".workorder-action.fa-clock-o").on("click",
			function()
			{
				$(this).removeClass("fa-clock-o").addClass("fa-circle-o-notch").addClass("fa-spin");
				
				var workorderID = $(this).attr("workorderID");
				var phone_number = $(this).attr("phone_number");
				document.location.href = "/extensions/workshop/library/php/posts/on_hold.php?workorderID=" + workorderID + "&phone_number=" + phone_number;
			}
		);
		
		$(".workorder-action.fa-exclamation").on("click",
			function()
			{
				var workorderID = $(this).attr("workorderID");
				
				popup(500, 260, "/extensions/workshop/modules/popup_notes.php?workorderID=" + workorderID);
			}
		);
		
		$(".workorder-action.fa-battery-1").on("click",
			function()
			{
				var workorderID = $(this).attr("workorderID");
				
				popup(300, 175, "/extensions/workshop/modules/popup_battery.php?workorderID=" + workorderID);
			}
		);
		
		
		
		
		
		/*
		**	Table control.
		*/
		
		var current = $("table.view").find("tbody").find("tr.active");
		
		if(current.length < 1)
		{
			$("div.register-screen").find("table.view").find("tbody").find("tr").first().addClass("active");
		}
		
		$("table.view").find("tbody").find("tr").on("click",
			function()
			{
				$("table.view").find("tbody").find("tr.active").removeClass("active");
				$(this).addClass("active");
			}
		);
		
		$("div.table-control").on("click",
			function()
			{
				var current = $("table.view").find("tbody").find("tr.active");
				
				var prev = current.prev("tr");
				var next = current.next("tr");
				
				if($(this).hasClass("up") && prev.length != 0)
				{
					current.removeClass("active");
					prev.addClass("active");
				}
				else if($(this).hasClass("down") && next.length != 0)
				{
					current.removeClass("active");
					next.addClass("active");
				}
				
				$("div.table-holder")
				.scrollTop(
					$("div.table-holder").scrollTop()
					+ current.position().top 
					- $("div.table-holder").height()/2 
					+ current.height()/2
				);
			}
		);
		
		$("div.select-control").on("click",
			function()
			{
				if($(this).hasClass("up"))
				{
					var val = $("select").find("option:selected");
					val = val.prev("option").val();
					
					if(val)
					{
						$("select").val(val);
						
						var select = $('select')[0];
						var opt = $('select option[value=' + $("select").find("option:selected").val() + ']')[0];

						$('select').find("option").removeAttr("selected");
						
						opt.selected = true;
						selectAndScrollToOption(select, opt);
					}
				}
				else if($(this).hasClass("down"))
				{
					var val = $("select").find("option:selected");
					val = val.next("option").val();
					
					if(val)
					{
						$("select").val(val);
						
						var select = $('select')[0];
						var opt = $('select option[value=' + $("select").find("option:selected").val() + ']')[0];

						$('select').find("option").removeAttr("selected");
						
						opt.selected = true;
						selectAndScrollToOption(select, opt);
					}

				}
			}
		);
		
		
		
		/*
		**	Others
		*/
		
		var val = 0;
		
		$(".grand_total").each(
			function()
			{
				val = val + parseFloat($(this).val());
			}	
		);
		
		val = val.toFixed(2);
		val = val.replace(".", ",");
		
		$("input#grand_total").val(val);
		
		
		
		/*
		**	Popup functions
		*/
		
		$("div.popup-container").find("div.closer").on("click",
			function()
			{
				$("div.popup-overlay").hide();
				$("div.popup-container").hide();
			}
		);
		
		$("input#close_popup").on("click",
			function()
			{
				$("div.popup-overlay", parent.document).hide();
				$("div.popup-container", parent.document).hide();
			}
		);
		
		$(document).keyup(
			function(e) 
			{
				if (e.keyCode == 27)
				{
					$("div.popup-overlay").hide();
					$("div.popup-container").hide();
					
					$("div.popup-overlay", parent.document).hide();
					$("div.popup-container", parent.document).hide();
				}
			}
		);
		
		$("div.popup-keyboard").on("click",
			function()
			{
				if($("input.popup-keyboard-output").hasClass("remove-default"))
				{
					$("input.popup-keyboard-output").val("").removeClass("remove-default");
				}
				
				var button = $(this).find("div.pos-button");
				var val = $("input.popup-keyboard-output").val();
				
				if($("input.popup-keyboard-output").hasClass("date-field"))
				{
					val = val.replace(/-/g, "");
				}
				
				if(button.hasClass("fa"))
				{
					if(button.hasClass("fa-backward"))
					{
						val = val.slice(0, -1);
					}
					else if(button.hasClass("fa-percent"))
					{
						button.removeClass("gray-text").addClass("red-text");
						$(".fa-euro").removeClass("red-text").addClass("gray-text");
						
						$("input#type").val("percent");
					}
					else if(button.hasClass("fa-euro"))
					{
						button.removeClass("gray-text").addClass("red-text");
						$(".fa-percent").removeClass("red-text").addClass("gray-text");
						
						$("input#type").val("price");
					}
					else if(button.hasClass("fa-minus"))
					{
						val = val + "-";
					}
					else if(button.hasClass("fa-floppy-o"))
					{
						$(this).closest("form").submit();
					}
					else if(button.hasClass("fa-text-width"))
					{
						val = val + " ";
					}
				}
				else
				{
					val = val + button.html();
				}
				
				if($("input.popup-keyboard-output").hasClass("date-field"))
				{
					var string = "";
					var check = val;
					
					// Dag
					if(check.length >= 2)
					{
						string = string + check.substring(0, 1);
						string = string + check.substring(2, 1);
						string = string + "-";
					}
					
					// Maand
					if(check.substring(3, 2) != "")
					{
						string = string + check.substring(3, 2);
					}
					
					if(check.substring(4, 3) != "")
					{
						string = string + check.substring(4, 3);
						string = string + "-";
					}
					
					// Jaar
					if(check.substring(5, 4) != "")
					{
						string = string + check.substring(5, 4);
					}
					
					if(check.substring(6, 5) != "")
					{
						string = string + check.substring(6, 5);
					}
					
					if(check.substring(7, 6) != "")
					{
						string = string + check.substring(7, 6);
					}
					
					if(check.substring(8, 7) != "")
					{
						string = string + check.substring(8, 7);
					}
					
					if(string != "")
					{
						val = string;
					}
				}
				
				$("input.popup-keyboard-output").val(val)
			}
		);
	}
);

function popup(width, height, url)
{
	//$("div.popup-container", parent.document).find("div.closer").trigger("click");
	
	$("div.popup-container", parent.document).find("div.closer").show();
	
	$("div.popup-container", parent.document)
		.css("width", width + "px")
		.css("height", height + "px")
		.css("margin-top", "-" + (height/2) + "px")
		.css("margin-left", "-" + (width/2) + "px");
	
	$("div.popup-container", parent.document)
		.find("iframe")
		.attr("src", url);
		
	$("div.popup-container, div.popup-overlay", parent.document).show();
}
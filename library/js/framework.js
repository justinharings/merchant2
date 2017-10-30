/*
**	Overall file for the framework to function. Control the menu,
**	top bar functions and content generator.
*/

$(document).ready(
	function()
	{
		/*
		**
		*/
		
		$("input.focus").focus();
		
		
		
		
		/*
		**	Remove loader when everything is done loading.
		*/
		
		$("div.loader").fadeOut("fast");
		
		
		
		/*
		**	Prevent a post event when ENTER is pressed inside
		**	a form. This is not handy when using barcode scanners
		**	or searching for data with the enter button.
		*/
		
		$(document).on("keypress", 'form',
			function (e) 
			{
				var code = e.keyCode || e.which;

				if(code == 13 && $(":focus").is("textarea") == false)
				{
					e.preventDefault();
					
					if($(":focus").attr("id") == "login_code")
					{
						
						$(this).submit();
					}
					
					return false;
				}
			}
		);
		
		
		
		/*
		**	Script for using TABS in forms. Activate the first tab automaticly
		**	and when a tab is pressed, switch the content.
		*/
		
		if($("div.simple-form").find("div.form-tabs").length > 0)
		{
			$("div.form-tabs").find("div:nth-child(2)").addClass("active");
			$("div.simple-form").find("div.tab").hide().first().show();
			
			$("div.simple-form").find("div.form-tabs").find("div").slice(1).on("click",
				function()
				{
					var index = $(this).index();
					
					$("div.simple-form").find("div.form-tabs").find("div").removeClass("active");
					$(this).addClass("active");
					
					$("div.simple-form").find("div.tab").hide();
					$("div.simple-form").find("div.tab-" + index).show();
				}
			);
			
			$(".activate-tab").on("click",
				function()
				{
					var tab = parseInt($(this).attr("tab")) + 1;
					$("div.simple-form").find("div.form-tabs").find("div:nth-child(" + tab + ")").trigger("click");
				}
			);
		}
		
		
		
		/*
		**	If, in a form, the dataID is set but the last caret
		**	of the breadcrumbs are empty, something is wrong.
		**	Return to the view then.
		**
		**	Since all the FORM files start with form-, I used this
		**	as a exploder. Needed to explode on something..
		*/
		
		var url = document.location.href;
		
		if($("ul.breadcrumbs").length > 0 && url.indexOf("/form-") > 0)
		{
			if	(
					$("form").find("input[type=hidden]").first().val() != 0
					&& $("ul.breadcrumbs").find("li").last().html().length <= 2
				)
			{
				var split = url.split("/form-");
				
				url = split[0];
				document.location.href = url;
			}
		}
		
		
		/*
		**
		*/
		
		$("input.pulldown").on("click",
			function(e)
			{
				e.preventDefault();
				
				var menu = $(this).attr("menu");
				
				var top = ($(this).offset().top + 38) + "px";
				var left = ($(this).offset().left + 10) + "px";
				
				$("div.pulldown." + menu)
					.css("top", top)
					.css("left", left)
					.show();
				
				setTimeout(
					function()
					{
						$("div.pulldown." + menu).hide();
					}, 5000
				);
			}
		);
		
		
		
		/*
		**
		*/
		
		$("div.pulldown").find("div.item").on("click",
			function()
			{
				if($(this).attr("window") != "")
				{
					window.open($(this).attr("window"));
				}
				else if($(this).attr("browse") != "")
				{
					document.location.href = $(this).attr("browse");
				}
			}
		);
		
		
		
		/*
		**	When clicked on the logout button, show the loader
		**	and unset all the sessions. Then return to the login page.
		*/
		
		$("span.logout-button").on("click",	
			function()
			{
				$(this).removeClass("fa-power-button").addClass("fa-spinner").addClass("fa-spin");
				
				$.post("/library/php/posts/authorization/logout.php").done(
					function(data)
					{
						if(data == 1)
						{
							document.location.href = "/";
						}
					}
				);
			}
		);
		
		$("span.logout-button-pos").on("click",	
			function()
			{
				$(this).removeClass("fa-power-button").addClass("fa-spinner").addClass("fa-spin");
				
				$.post("/library/php/posts/authorization/logout.php").done(
					function(data)
					{
						if(data == 1)
						{
							document.location.href = "/pos/";
						}
					}
				);
			}
		);
		
		$("span.logout-button-workshop").on("click",	
			function()
			{
				$(this).removeClass("fa-power-button").addClass("fa-spinner").addClass("fa-spin");
				
				document.location.href = '/pos/modules/workorders/';
			}
		);
		
		
		
		/*
		**	On mobile pages, the history -1 button is showed. This allows the user to
		**	return to the previous page while using the web app.
		*/
		
		$("span.previous-button, input#return").on("click",	
			function()
			{
				if($(this).hasClass("fa-previous-button"))
				{
					$(this).removeClass("fa-previous-button").addClass("fa-spinner").addClass("fa-spin");
				}
				
				window.history.back();
			}
		);
		
		
		
		/*
		**	When the 'Enter' button is pressed in a search input,
		**	get the data and post is with an URL.
		*/
		
		$("input#search").focus();
		
		$("input#search").on("keypress",
			function(e)
			{
				if(e.which == 13)
				{
					var str = document.location.href;
					var match = str.match("/search/");
					var url;
					
					if(match != "" && match != null)
					{
						url = str.split("search/");
						url = url[0];
					}
					else
					{
						url = document.location.href;
					}
					
					if($(this).val() == "")
					{
						document.location.href = url;
					}
					else
					{
						document.location.href = url + 'search/' + $(this).val() + '/';
					}
				}
			}
		);
		
		
		
		/*
		**	In the mobile version, show the menu when
		**	the users presses the 'bars' icon in the corner.
		*/
		
		$("span.fa-bars").on("click",
			function()
			{
				if($("div.menu").css("left") == "0px")
				{
					$("div.menu").removeAttr("style");
				}
				else
				{
					$("div.menu").css("left", "0px");
				}
			}
		);
		
		
		
		/*
		**	Automaticly add a caret after each breadcrumb option,
		**	leaving the HTML code clean and simple.
		*/
		
		$("ul.breadcrumbs").find("li").slice(1).each(
			function()
			{
				$(this).prepend('<span class="fa fa-caret-right"></span>');
			}	
		);
		
		
		
		/*
		**	Prepare the menu. Each active item must receive the
		**	'active' class. Define if a element is active using the URL.
		**	Also bind the location URL depending on the attribute 'rel'.
		*/
		
		$("li.menu-item").each(
			function()
			{
				var str = document.location.href;
				var match = str.match($(this).attr("rel"));
				
				if(match != "" && match != null)
				{
					$(this).find("div.icon").addClass("active");
					$(this).find("div.text").addClass("active");
					
					$(this).parent().parent().prev("li").find("div.icon").addClass("active");
					$(this).parent().parent().prev("li").find("div.text").addClass("active");
					
					var offset = $("div.icon.active").first().offset();
					offset.top -= 100;
					
					$("div.menu-items-holder").scrollTop(offset.top);
				}
				
				if($(this).attr("rel") != "" && typeof $(this).attr("rel") != 'undefined')
				{
					$(this).bind("click",
						function()
						{
							var prefix = $("input#_language_pack").val();
							
							if($(this).hasClass("pos"))
							{
								prefix = "pos";
							}
							else if($(this).hasClass("workshop"))
							{
								prefix = "workshop";
							}
							
							document.location.href = "/" + prefix + "/modules" + $(this).attr("rel");
						}
					);
				}
			}	
		);
		
		
		
		
		/*
		**	Add each table with the class 'view' to a special DIV
		**	that designs a good looking data table.
		*/
		
		var cnt = 0;
		
		$("table.view").each(
			function()
			{
				$(this).before('<div class="table-holder n-' + cnt + '"></div>');
				$(this).appendTo($("div.table-holder.n-" + cnt));
				
				cnt++;
			}
		);
		
		
		
		/*
		**	Each TD that has a attribute called 'link' must have
		**	the value of this attribute binded as a onclick URL.
		*/
		
		$("table.view").find("tbody").find("tr").each(
			function()
			{
				if($(this).attr("click") != "" && typeof $(this).attr("click") !== "undefined")
				{
					var link = $(this).attr("click");
					
					$(this).bind("click",
						function()
						{
							document.location.href = link;
						}
					);
				}
			}
		);
		
		
		
		/*
		**	The same kind of function as the one above. When a button
		**	div is clicked and it has a attribute called "click",
		**	browse to the page added in click.
		*/
		
		$("div.button").each(
			function()
			{
				if($(this).attr("click") != "" && typeof $(this).attr("click") !== "undefined")
				{
					var link = $(this).attr("click");
					
					$(this).bind("click",
						function()
						{
							document.location.href = link;
						}
					);
				}
			}
		);
		
		
		
		/*
		**	When this span button is clicked, add a row
		**	to the form table. 
		*/
		
		var cnt = 0;
		
		$("span.add-row").on("click",
			function()
			{
				cnt += 1;
				
				console.log(cnt);
				
				var tbody = $(this).closest("table").find("tbody");
				var clone = tbody.find("tr.new-row").clone();
				
				clone.find("input, select").each(
					function()
					{
						var str = $(this).attr("id");
						
						if(str.indexOf("_+") >= 0)
						{
							var replaced = str.replace("_+", "_" + cnt)
							$(this).attr("id", replaced);
						}
						
						$(this).removeClass("hasDatepicker");
					}
				);
				
				clone.appendTo(tbody).removeClass("new-row").show();
				
				$("input, select, textarea").each(
					function()
					{
						if($(this).parents('tr.new-row').length)
						{
							return;
						}
						
						systemChanges($(this));
					}
				);
				
				checkboxHandler();
				findProduct();
			}
		);
		
		
		
		/*
		**
		*/
		
		$("span.remove-row").on("click",
			function()
			{
				if($(this).attr("post") != "")
				{
					var msg = "Are you sure mate?";
					
					if(confirm(msg))
					{					
						document.location.href = $(this).attr("post");
					}
				}
			}
		);
		
		
		
		/*
		**	Search for product information out of the form-table
		**	option. It's possible that the product information
		**	is used for data showing.
		*/
		
		$(document).on("keypress", 'input.product-search',
			function (e) 
			{
				var code = e.keyCode || e.which;
				var elm = $(this);
				
				if (code == 13) 
				{
					e.preventDefault();
					
					elm.prev("span.fa")
						.removeClass("fa-search")
						.addClass("fa-circle-o-notch")
						.addClass("fa-spin");
					
					$.post(
						"/library/php/posts/catalogus/return_product.php",
						{
							article_code: elm.val()
						}
					).done(
						function(data) 
						{
							if(data == "null")
							{
								elm.val("");
								elm.css("border", "1px solid #d00000");
								
								elm.prev("span.fa").addClass("fa-search").removeClass("fa-circle-o-notch").removeClass("fa-spin");
							}
							else
							{
								data = $.parseJSON(data);
								
								var tr = elm.parent().parent().parent();
								var productID = data['productID'];
								productID = '<input type="hidden" name="productID[]" id="productID" value="'+productID+'" />';
								
								tr.find("td.searched-p-name").html(data['name']);
								tr.find("td.searched-p-barcode").html(data['barcode']);
								tr.find("td.searched-p-article-code").html(data['article_code']);
								tr.find("td.searched-p-price").html("&euro;&nbsp;" + data['price']);
								tr.find("td.searched-p-productID").html(productID);
								
								tr.find("input.searched-p-price").val(data['price']);
								tr.find("input.searched-p-name").val(data['name']);
								tr.find("input.searched-p-taxrate").val(data['taxrate']);
							}
						}
					);
				}
			}
		);

		$(document).on("change", 'select.shipment-search',
			function (e) 
			{
				var elm = $(this);
				
				$.post(
					"/library/php/posts/catalogus/return_shipment.php",
					{
						shipmentID: elm.val()
					}
				).done(
					function(data) 
					{
						data = $.parseJSON(data);
						
						var tr = elm.parent().parent().parent();
						
						tr.find("td.searched-s-price").html(data['price']);
						tr.find("td.searched-s-courier").html(data['courier']);
						
						tr.find("input.searched-s-price").val(data['price']);
						tr.find("input.searched-s-courier").val(data['courier']);
					}
				);
			}
		);
	}
);
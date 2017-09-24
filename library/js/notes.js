$(document).ready(
	function($)
	{
		if($("div.tab.js-load-notes").length >= 0)
		{
			loadCustomerNotes();
		}
	}
);

function saveCustomerNotes()
{
	var orderID = 0;
	var elm = $("div.tab.js-load-notes").attr("orderID");
	
	if(elm != "")
	{
		orderID = elm;
	}	
	
	$("textarea#note").css("border-color", "");
	
	if($("textarea#note").val() == "")
	{
		$("textarea#note").css("border-color", "#d00000");
	}
	else
	{
		setTimeout(
			function()
			{
				$.post(
					"/library/php/posts/klanten/save_notes.php",
					{
						customerID: $("input#customerID").val(),
						orderID: orderID,
						note: $("textarea#note").val()
					}
				).done(
					function(data) 
					{
						loadCustomerNotes();
					}
				);	
			}, 1000
		);
	}
	
	setTimeout(
		function()
		{
			$("textarea#note").val("");
			$("input#save_note").val($("input#save_note").attr("original")).removeClass("no-action");
			
			$('div.content').animate(
				{
					scrollTop: 200
				}, 1000
			);
		}, 1500
	);
}

function loadCustomerNotes()
{
	$("div.loaded-note").remove();
	
	var orderID = 0;
	var elm = $("div.tab.js-load-notes").attr("orderID");
	
	if(elm != "")
	{
		orderID = elm;
	}
	
	$.post(
		"/library/php/posts/klanten/load_notes.php",
		{
			customerID: $("input#customerID").val(),
			orderID: orderID
		}
	).done(
		function(data) 
		{
			data = $.parseJSON(data);
			
			for(var i = 0; i < data.length; i++)
			{
				var html = "";
				var color_class = "blue";
				
				if(data[i]['orderID'] > 0)
				{
					var url = "/" + $("input#_language_pack").val() + "/modules/verkoop/openstaand/form-order/" + data[i]['orderID'];
					
					html = "<strong>Bestelling <a href=\"" + url + "\">#" + data[i]['orderID'] + "</a></strong><br/>";
					color_class = "gray";
				}
				
				html += data[i]['date_added'];
				
				var div = $("<div/>")
					.addClass("form-content")
					.addClass("loaded-note")
					.addClass(color_class)
					.html(data[i]['content'])
					.hide()
					.appendTo("div.tab.js-load-notes")
					.fadeIn("fast");
				
				$("<div/>")
					.addClass("content-header")
					.html(html)
					.prependTo(div);
			}
		}
	);
}
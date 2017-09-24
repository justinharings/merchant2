$(document).ready(
	function($)
	{
		if($("div.tab.js-load-emails").length >= 0 && $("div.tab.js-load-emails").attr("e-mail-customer-id") != '')
		{
			loadEmails($("div.tab.js-load-emails").attr("e-mail-customer-id"));
		}
	}
);

function sendEmail()
{
	$("input#email_customerID, input#email_sender, input#email_receiver, input#email_subject, textarea#email_content").css("border-color", "");
	
	var valid = true;
	
	if($("input#email_customerID").val() == "")
	{
		$("input#email_customerID").css("border-color", "#d00000");
		valid = false;
	}
	
	if($("input#email_sender").val() == "")
	{
		$("input#email_sender").css("border-color", "#d00000");
		valid = false;
	}
	
	if($("input#email_receiver").val() == "")
	{
		$("input#email_receiver").css("border-color", "#d00000");
		valid = false;
	}
	
	if($("input#email_subject").val() == "")
	{
		$("input#email_subject").css("border-color", "#d00000");
		valid = false;
	}
	
	if($("textarea#email_content").val() == "")
	{
		$("textarea#email_content").css("border-color", "#d00000");
		valid = false;
	}
	
	if(valid == true)
	{
		setTimeout(
			function()
			{
				$.post(
					"/library/php/posts/mailserver/send.php",
					{
						customerID: $("input#email_customerID").val(),
						sender: $("input#email_sender").val(),
						receiver: $("input#email_receiver").val(),
						subject: $("input#email_subject").val(),
						content: $("textarea#email_content").val()
					}
				).done(
					function(data) 
					{
						loadEmails($("input#email_customerID").val());
					}
				);	
			}, 1000
		);
	}
	
	setTimeout(
		function()
		{
			$("input#email_sender, input#email_subject, textarea#email_content").val("");
			$("input#send_email").val($("input#send_email").attr("original")).removeClass("no-action");
			
			$('div.content').animate(
				{
					scrollTop: 200
				}, 1000
			);
		}, 1500
	);
}

function loadEmails(customerID)
{
	$("div.loaded-email").remove();
	
	$.post(
		"/library/php/posts/klanten/load_emails.php",
		{
			customerID: customerID
		}
	).done(
		function(data) 
		{
			data = $.parseJSON(data);
			
			for(var i = 0; i < data.length; i++)
			{
				var div = $("<div/>")
					.addClass("form-content")
					.addClass("loaded-email")
					.addClass("blue")
					.html(data[i]['content'])
					.hide()
					.appendTo("div.tab.js-load-emails")
					.fadeIn("fast");
				
				var html = data[i]['date_added'] + "&nbsp;&nbsp;-&nbsp;&nbsp;" + data[i]['receiver'];
				
				$("<div/>")
					.addClass("content-header")
					.html(html)
					.prependTo(div);
			}
		}
	);
}
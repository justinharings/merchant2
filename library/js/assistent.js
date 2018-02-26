$(document).ready(
	function($)
	{
		if($("body").hasClass("black"))
		{
			setTimeout(
				function()
				{
					document.location.href = document.location.href;
				}, 480000
			);
		}
		
		var scroll = 0;
		
		$("div.scroll.down").on("click",
			function()
			{
				if($('div.container').scrollTop() < scroll)
				{
					return false;
				}
				
				scroll += 100;
				
				$('div.container').scrollTop(scroll);
				
				console.log("Scroll is " + scroll);
			}
		);
		
		$("div.scroll.up").on("click",
			function()
			{
				if(scroll < 0)
				{
					return false;
				}
				
				scroll -= 100;
				
				$('div.container').scrollTop(scroll);
				
				console.log("Scroll is " + scroll);
			}
		);
		
		$("body.black").on("click",
			function()
			{
				document.location.href = document.location.href;
			}
		);
		
		$("div.save-button.post").on("click",
			function()
			{
				if($(this).attr("form-action") != "")
				{
					$("form#post").attr("action", $(this).attr("form-action"));
				}
				
				$("form#post").submit();
			}
		);
		
		$("div.save-button.calendar").on("click",
			function()
			{
				popup(318, 340, "/extensions/assistent/modules/popup_order_date.php?orderID=" + $("div.save-button.calendar").attr("orderID"));
			}
		);
		
		$("div.overlay").on("click",	
			function()
			{
				if($(this).hasClass("white"))
				{
					$(this).nextAll("input.status").first().val(2);
					$(this).addClass("red").removeClass("white");
				}
				else if($(this).hasClass("red"))
				{
					$(this).nextAll("input.status").first().val(0);
					$(this).removeClass("red");
				}
				else
				{
					$(this).nextAll("input.status").first().val(1);
					$(this).addClass("white");
				}
			}
		);
	}
);
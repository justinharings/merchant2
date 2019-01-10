$(document).ready(
	function($)
	{
		$("div.filler").on("click",
			function()
			{
				document.location.href = '/assistent2/?module=calendar';
			}
		);
		
		$("*").on("click",
			function()
			{
				var browse = $(this).attr('browse');
			
				if (typeof browse !== typeof undefined && browse !== false) 
				{
					document.location.href = $(this).attr("browse");
				}
			}
		);
		
		$('input.datepicker').datepicker(
			{
				dateFormat: 'yy-mm-dd',
				onSelect: function(dateText, inst)
				{
					$('#date_form').submit();
				}
			}
		);
		$('li.datepicker').click(
			function() 
			{
				$('input.datepicker').show().focus().hide();
			}
		);
		
		$("div.menu-button").on("click",
			function()
			{
				$(this).animate(
					{
						deg: 90
					},
					{
						duration: 200,
						step: function(now) 
						{
							$(this).css(
								{
									transform: 'rotate(' + now + 'deg)' 
								}
							);
						}
					}
				);
				
				setTimeout(
					function()
					{
						$("div.menu").fadeIn("fast").css("display", "table");
					}, 100
				);
			}
		);
		
		$("div.menu").find("li").on("click",
			function()
			{
				$("div.menu").hide();
				$("div.menu-button").removeClass("fa-bars").addClass("fa-spin").addClass("fa-spinner");
			}
		);
		
		$("div.button").on("click",
			function()
			{
				$("div.button").hide();
			}
		);
		
		$("div.button.refresh").on("click",
			function()
			{
				document.location.href = '/assistent2/';
			}
		);
		
		$("div.button.vuurwerk").on("click",
			function()
			{
				document.location.href = '/vwass/';
			}
		);
		
		$("div.button.calendar").on("click",
			function()
			{
				document.location.href = '/assistent2/?module=calendar';
			}
		);
		
		setTimeout(
			function()
			{
				document.location.reload();
			}, 30000
		);
	}
);
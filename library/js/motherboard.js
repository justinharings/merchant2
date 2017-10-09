$(document).ready(
	function()
	{
		$.getScript("/library/js/datepicker.minified.js", 
			function()
			{
				console.log("Datepicker.minified.js file loaded.");
			}
		);	
		
		$.getScript("/library/js/input.minified.js", 
			function()
			{
				console.log("Input.minified.js file loaded.");
			}
		);
		
		$.getScript("/library/js/framework.minified.js", 
			function()
			{
				console.log("Framework.minified.js file loaded.");
			}
		);
		
		$.getScript("/library/js/multiselect.minified.js", 
			function()
			{
				console.log("Multiselect.minified.js file loaded.");
			}
		);
		
		$.getScript("/library/js/notes.minified.js", 
			function()
			{
				console.log("Notes.minified.js file loaded.");
			}
		);
		
		$.getScript("/library/js/emails.minified.js", 
			function()
			{
				console.log("Email.minified.js file loaded.");
			}
		);
		
		$.getScript("/library/js/sms.minified.js", 
			function()
			{
				console.log("Sms.minified.js file loaded.");
			}
		);
	}
);
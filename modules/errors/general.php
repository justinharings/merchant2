<!DOCTYPE html>
<html lang="EN">
	<head>
		<title>Sorry, we broke something..</title>
		
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta http-equiv="Content-Language" content="EN" />
		
		<meta name="robots" content="index, follow" />

		<link type="image/x-icon" rel="icon" href="/library/media/favicon.png" />
		<link type="image/x-icon" rel="shortcut icon" href="/library/media/favicon.png" />
		
		<link rel="stylesheet" type="text/css" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" />
		<link rel="stylesheet" type="text/css" href="/library/css/motherboard.minified.css" />

		<script type="text/javascript" src="//code.jquery.com/jquery-latest.js"></script>
	</head>

	<body>
		<div class="error-image">
			<img src="/library/media/error.png" />
			
			<div class="error-title">
				Oh no.. We broke something!
			</div>
			
			<div class="error-text">
				We hope we're back online soon.. Something went very wrong!<br/>
				Sorry about this.. Please try <a href="<?= "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" ?>">this link</a> from time to time.
			</div>
		</div>
	</body>
</html>
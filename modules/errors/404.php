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
			<img src="/library/media/404.png" />
			
			<div class="error-title">
				Wrong way mate.. That link doesn't work!
			</div>
			
			<div class="error-text">
				If you typed this link yourself, you probably misspelled something.<br/>
				You can always visit your starting page right <a href="/<?= _LANGUAGE_PACK ?>/modules<?= $_SESSION['start_page'] ?>">here</a>!
			</div>
		</div>
	</body>
</html>
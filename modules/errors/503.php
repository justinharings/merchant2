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
			<img src="/library/media/503.png" />
			
			<div class="error-title">
				Nope nope nope.. No permission for you!
			</div>
			
			<div class="error-text">
				I don't know what you're trying to do, but you can't be here..<br/>
				You can always visit your starting page right <a href="/<?= _LANGUAGE_PACK ?>/modules<?= $_SESSION['start_page'] ?>">here</a>!
			</div>
		</div>
	</body>
</html>
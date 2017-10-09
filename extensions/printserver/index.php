<!DOCTYPE html>
<html lang="nl">
	<head>
		<title>Cloud printer ...</title>
		
		<link rel="stylesheet" type="text/css" href="/library/css/motherboard.minified.css" />
		
		<style type="text/css">
			span.fa-cog
			{
				position: absolute;
				top: 50%;
				left: 50%;
				
				margin: -85px 0px 0px 20px;
				
				font-size: 50px;
				color: #d00000;
			}
			
			span.fa-print
			{
				width: 100px;
				height: 100px;
				
				position: absolute;
				top: 50%;
				left: 50%;
				
				margin: -50px 0px 0px -50px;
				
				font-size: 100px;
				color: #000;
			}
		</style>
	</head>

	<body>
		<span class="fa fa-spin fa-cog"></span>
		<span class="fa fa-print"></span>
		
		<script type="text/javascript">
			setTimeout(
				function()
				{
					document.location.href = '/extensions/printserver/router.php?<?= $_SERVER['QUERY_STRING'] ?>';
				}, 1000
			);
		</script>
	</body>
</html>	
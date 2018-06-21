<!DOCTYPE html>
<html lang="nl">
	<head>
		<title>Admin</title>
		
		<script type="text/javascript" src="//code.jquery.com/jquery-latest.js"></script>
		
		<script type="text/javascript">
			$(document).ready(
				function($)
				{
					$("#code, #company_name").focus();
				}
			);
		</script>
	</head>

	<body>
		<?php
		if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['company_name']))
		{
			define("_LANGUAGE_PACK", "nl");
			
			require_once("/var/www/vhosts/justinharings.nl/merchant.justinharings.nl/library/php/classes/motherboard.php");
			require_once("/var/www/vhosts/justinharings.nl/merchant.justinharings.nl/library/php/classes/database.php");
			require_once("/var/www/vhosts/justinharings.nl/merchant.justinharings.nl/library/php/classes/authorization.php");
			
			$database = new database();
			$auth = new authorization();
			
			$query = sprintf(
				"	INSERT INTO		merchant
					SET				merchant.company_name = '%s',
									merchant.address = '%s',
									merchant.zip_code = '%s',
									merchant.city = '%s',
									merchant.sms_sender = '%s',
									merchant.website_url = '%s',
									merchant.webshop_success_url = '/service/success.html',
									merchant.webshop_cancel_url = '/library/php/routers/canceled.php',
									merchant.email_address = '%s',
									merchant.phone = '%s',
									merchant.date_added = NOW()",
				$_POST['company_name'],
				$_POST['address'],
				$_POST['zip_code'],
				$_POST['city'],
				$_POST['sms_sender'],
				$_POST['website_url'],
				$_POST['email_address'],
				$_POST['phone']
			);
			$database->query($query);
			
			$merchantID = $database->insert_id();
			
			$query = sprintf(
				"	INSERT INTO		users
					SET				users.merchantID = %d,
									users.admin = 1,
									users.first_name = 'Justin',
									users.email_address = '%s',
									users.password = '%s',
									users.language_pack = 'nl',
									users.start_page = '/verkoop/openstaand/',
									users.date_added = NOW()",
				$merchantID,
				$_POST['email_address'],
				$auth->hashPassword("CentreVille9")
			);
			$database->query($query);
			
			$userID = $database->insert_id();
			
			$query = sprintf(
				"	INSERT INTO		users_permissions (userID, code)
					SELECT			%d, users_permissions.code
					FROM			users_permissions
					WHERE			users_permissions.userID = 1",
				$userID
			);
			$database->query($query);
			
			header("location: /admin.php");
		}
		else if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['code']) && $_POST['code'] == "78300")
		{
			?>
			<br/><br/><br/><br/><br/><br/><br/>
			<center>
				<form method="post" method="post">
					<input type="text" name="company_name" id="company_name" placeholder="Bedrijfsnaam" style="width: 200px; padding: 10px; text-align: center" /><br/>
					<input type="text" name="address" id="address" placeholder="Adres" style="width: 200px; padding: 10px; text-align: center" /><br/>
					<input type="text" name="zip_code" id="zip_code" placeholder="Postcode" style="width: 200px; padding: 10px; text-align: center" /><br/>
					<input type="text" name="city" id="city" placeholder="Stad" style="width: 200px; padding: 10px; text-align: center" /><br/>
					<br/>
					<input type="text" name="sms_sender" id="sms_sender" placeholder="SMS Afzender" style="width: 200px; padding: 10px; text-align: center" /><br/>
					<input type="text" name="website_url" id="website_url" placeholder="Website URL" style="width: 200px; padding: 10px; text-align: center" /><br/>
					<input type="text" name="email_address" id="email_address" placeholder="E-mail adres" style="width: 200px; padding: 10px; text-align: center" /><br/>
					<input type="text" name="phone" id="phone" placeholder="Telefoonnummer" style="width: 200px; padding: 10px; text-align: center" /><br/>
					<br/>
					<input type="submit" name="submit" id="submit" value="Gegevens opslaan" />
				</form>
			</center>
			<?php
		}
		else
		{
			?>
			<br/><br/><br/><br/><br/><br/><br/>
			<center>
				<form method="post" method="post">
					<input type="password" name="code" id="code" style="padding: 10px; text-align: center" />
				</form>
			</center>
			<?php
		}
		?>
	</body>
</html>	
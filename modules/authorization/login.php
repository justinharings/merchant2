<img src="/library/media/login/coffee.png" class="coffee" />
<img src="/library/media/login/specs.png" class="specs" />
<img src="/library/media/login/supplies.png" class="supplies" />
<img src="/library/media/login/paper.png" class="paper" />

<div class="login-container">
	<form method="post" action="/library/php/posts/authorization/login.php">
		<img src="/library/media/logo.png" />
		
		<input type="text" name="username" id="username" value="" class="width-100-percent margin" />
		<input type="password" name="password" id="password" value="" class="width-150" />
		<input type="submit" name="submit" id="submit" value="<?= $mb->_translateReturn("buttons", "login") ?>" class="width-140 red show-load" />
	</form>
</div>
<div class="center text-center" style="width:500px">
	<form method="post" action="/account/login">
		<?php if (isset($error)): ?>
			<small class="error"><?= $error ?></small>
		<?php endif ?>
		
		<label for="login-email">Email</label>
		<input type="email" id="login-email" name="email" required/>
		
		<label for="login-password">Пароль</label>
		<input type="password" id="login-password" name="password" required/>
		
		<input type="submit" class="big-btn orange-btn center" style="display: inline-block" value="Войти" /> или <a href="/account/register">зарегистрироваться</a>
	</form>
</div>
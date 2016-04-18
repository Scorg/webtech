<form action="/register" method="post">
	<input name="hash" type="hidden" value="<?= $hash ?>" />
	<?php if (!empty($error_hash)): ?>
		<small class="error"><?= $error_hash ?></small>
	<?php endif ?>
	
	<label for="user-name">Имя</label>
	<input id="user-name" type="text" name="firstname" value="<?= $firstname ?>" class="<?= empty($error_firstname) ? '' :'error' ?>" required/>
	<?php if (!empty($error_firstname)): ?>
		<small class="error"><?= $error_firstname ?></small>
	<?php endif ?>
	
	<label for="user-surname">Фамилия</label>
	<input id="user-surname" type="text" name="lastname" value="<?= $lastname ?>"/>
	
	<label for="user-email">Email</label>
	<input id="user-email" type="email" name="email" value="<?= $email ?>" class="<?= empty($error_email) ? '' :'error' ?>" required/>
	<?php if (!empty($error_email)): ?>
		<small class="error"><?= $error_email ?></small>
	<?php endif ?>
	
	<label for="user-country">Страна</label>
	<input id="user-country" type="text" name="country" value="<?= $country ?>"/>
	
	<label for="user-password">Пароль</label>
	<input id="user-password" type="password" name="password" class="<?= empty($error_password) ? '' :'error' ?>" required/>
	<?php if (!empty($error_password)): ?>
		<small class="error"><?= $error_password ?></small>
	<?php endif ?>
	
	<label for="user-password-confirm">Подтверждение пароля</label>
	<input id="user-password-confirm" type="password" name="password_confirm" class="<?= empty($error_password_confirm) ? '' :'error' ?>" required/>
	<?php if (!empty($error_password_confirm)): ?>
		<small class="error"><?= $error_password_confirm ?></small>
	<?php endif ?>
	
	<input type="submit" class="orange-btn big-btn" value="Зарегистрироваться"/>
</form>	
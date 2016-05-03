<form action="/account/register" method="post">
	<input name="hash" type="hidden" value="<?= $hash ?>" />
	<?php if (isset($error) && !empty($error['hash'])): ?>
		<small class="error"><?= $error['hash'] ?></small>
	<?php endif ?>
	<?php if (isset($error) && !empty($error['other'])): ?>
		<small class="error"><?= $error['other'] ?></small>
	<?php endif ?>
	
	<label for="user-name">Имя</label>
	<input id="user-name" type="text" name="firstname" value="<?= $firstname ?>" class="<?= empty($error['firstname']) ? '' :'error' ?>" required/>
	<?php if (isset($error) && !empty($error['firstname'])): ?>
		<small class="error"><?= $error['firstname'] ?></small>
	<?php endif ?>
	
	<label for="user-surname">Фамилия</label>
	<input id="user-surname" type="text" name="lastname" value="<?= $lastname ?>"/>
	
	<label for="user-email">Email</label>
	<input id="user-email" type="email" name="email" value="<?= $email ?>" class="<?= empty($error['email']) ? '' :'error' ?>" required/>
	<?php if (isset($error) && !empty($error['email'])): ?>
		<small class="error"><?= $error['email'] ?></small>
	<?php endif ?>
	
	<label for="user-country">Страна</label>
	<input id="user-country" type="text" name="country" value="<?= $country ?>"/>
	
	<label for="user-password">Пароль</label>
	<input id="user-password" type="password" name="password" class="<?= empty($error['password']) ? '' :'error' ?>" required/>
	<?php if (isset($error) && !empty($error['password'])): ?>
		<small class="error"><?= $error['password'] ?></small>
	<?php endif ?>
	
	<label for="user-password-confirm">Подтверждение пароля</label>
	<input id="user-password-confirm" type="password" name="password_confirm" class="<?= empty($error['password_confirm']) ? '' :'error' ?>" required/>
	<?php if (isset($error) && !empty($error['password_confirm'])): ?>
		<small class="error"><?= $error['password_confirm'] ?></small>
	<?php endif ?>
	
	<input type="submit" class="orange-btn big-btn" value="Зарегистрироваться"/>
</form>	
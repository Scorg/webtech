<form action="/article/<?= $action ?>" method="post">
	<?php if (isset($error) && !empty($error['other'])): ?>
		<small class="error"><?= $error['other'] ?></small>
	<?php endif ?>
	
	<label for="article-title">Заголовок</label>
	<input id="article-title" type="text" name="article[title]" value="<?= $title ?>" class="<?= empty($error['title']) ? '' :'error' ?>" required />
	<?php if (isset($error) && !empty($error['title'])): ?>
		<small class="error"><?= $error['title'] ?></small>
	<?php endif ?>
	
	<label for="article-text">Текст</label>
	<textarea id="article-text" name="article[text]" value="" class="<?= empty($error['text']) ? '' :'error' ?>"><?= $text ?></textarea>
	<?php if (isset($error) && !empty($error['text'])): ?>
		<small class="error"><?= $error['text'] ?></small>
	<?php endif ?>
	
	<input type="submit" class="orange-btn big-btn" value="<?= isset($id) ? 'Сохранить' : 'Отпраить' ?>"/>
</form>	
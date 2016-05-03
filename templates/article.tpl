<article>
	<div class="article-title"><?= $title ?></div>
	<div class="article-date"><?= $date ?></div>
	<div class="article-author"><?= $author ?></div>
	<?php if (isset($controls['edit'])): ?>
		<a href="/article/<?= $id ?>/edit" class=""><?= $controls['edit'] ?></a>
	<?php endif ?>
	<?php if (isset($controls['delete'])): ?>
		<a href="/article/<?= $id ?>/delete" class=""><?= $controls['delete'] ?></a>
	<?php endif ?>
	<div class="article-text"><?= $text ?></div>
</article>	
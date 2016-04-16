<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"/>
	<title><?= $title ?></title>
	<link type="text/css" rel="stylesheet" href="/css/style.css"/>
</head>

<body>
<div id="body-wrapper">
	<header>
		<div class="container center">
			<!--div class="logo"-->
				<span class="logo">Бложик</span>
			<!--/div-->
		
			<nav class="pull-right">
			<ul class="inline middle">
				<li><a href="/">Главная</a></li>
				<li><a href="/about">О нас</a></li>
				<li><a href="/contact">Контакты</a></li>			
			</ul>
			</nav>
		</div>
	</header>
	
	<div id="page" class="container center">	
		<aside>
			<?= $aside ?>
		</aside>
		
		<div id="content">	
			<?= $content ?>
		</div>		
	</div>
	
	<footer>
		<div class="container center">
		&copy; Гудков Виктор 2016, некоторые права защищены
		</div>
	</footer>
</div>
</body>
</html>
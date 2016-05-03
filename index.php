<?php
//header("Content-type: text/html; charset: utf-8;");
require_once 'config.php';

require_once 'core/registry.php';
require_once 'core/loader.php';
require_once 'core/router.php';
require_once 'core/controller.php';
require_once 'core/model.php';
require_once 'core/request.php';
require_once 'core/response.php';



// БД
$db = new PDO(DB_PROVIDER . ':host=' . DB_HOST . ';dbname=' . DB_BASE, DB_USER, DB_PASSWORD);
// unset(DB_HOST);
// unset(DB_USER);
// unset(DB_PASSWORD);

// Устанвока
if (isset($_GET['lolparamsblabla']) && $_GET['lolparamsblabla']=='install') {
	require_once 'install/install.php';
	Installer::install($db);
}

session_start([
	'coockie_lifetime' => 1200
]);

$registry = new Registry();

$request = new Request();
$response = new Response();
$loader = new Loader($registry);

$registry['db'] = $db;
$registry['load'] = $loader;
$registry['request'] = $request;
$registry['response'] = $response;

$response->addHeader("Content-type: text/html; charset=utf-8;");


//Находим и вызываем контроллер
Router::execute($registry);


//Отображаем вывод (страницу)
$response->output();
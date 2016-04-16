<?php
//header("Content-type: text/html; charset: utf-8;");

define('SITE_ROOT', __DIR__ . '/');
define('MODELS_ROOT', SITE_ROOT . 'models/');
define('CONTROLLERS_ROOT', SITE_ROOT . 'controllers/');
define('CORE_ROOT', SITE_ROOT . 'core/');
define('TEMPLATES_ROOT', SITE_ROOT . 'templates/');


//require_once 'core/registry.php';
require_once 'core/router.php';
require_once 'core/controller.php';
require_once 'core/request.php';
require_once 'core/response.php';


//Создаём объекты
$db = new PDO('mysql:host=localhost;dbname=asu13', 'uiti', 'piska');
$request = new Request();
$response = new Response();

$registry = array();
$registry['db'] = $db;
$registry['request'] = $request;
$registry['response'] = $response;

$response->addHeader("Content-type: text/html; charset: utf-8;");


//Находим и вызываем контроллер
Router::execute($registry);


//Отображаем вывод (страницу)
$response->output();
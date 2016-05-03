<?php
class Router
{
	static function execute($data) 
	{
		$pieces = explode('/', str_replace('../', '', (trim(/*$_SERVER['REQUEST_URI']*/$data['request']->get['_route_'], '/\\'))));
		
		$fullpath = '';
		$cmd_path = '';
		$controllerName = 'index';
		
		//print_r($pieces);
		
		foreach ($pieces as $piece) {
			$fullpath = CONTROLLERS_ROOT . $cmd_path . $piece;
			
			if ($piece == '..') {
				array_shift($pieces);
				continue;
			}
			
			if (is_dir($fullpath)) {
				$cmd_path .= $piece . '/';
				array_shift($pieces);
				continue;
			}

			// Находим файл
			if (is_file($fullpath . '.php')) {
				$controllerName = $piece;
				array_shift($pieces);
				break;
			}
		}
		
		$modelName = /*'Model' . */$controllerName;
		
		$modelClassName = 'Model' . $controllerName;
		$controllerClassName = 'Controller' . $controllerName;
		
		// Заглядываем, что нам дальше указали
		$method = $pieces[0];
		
		//Не даём вызывать магические методы (мы дописываем 'action_', надо ли это вообще делать?
		if (substr($method, 0, 2) == '__') {
			return false;
		}
		
		if (empty($method)) {
			$method = 'index';
		}
		
		$fileWithController = strtolower($controllerName) . '.php';
		$fileWithControllerPath = CONTROLLERS_ROOT . $cmd_path . $fileWithController;
						
		if (file_exists($fileWithControllerPath)) {
			include_once $fileWithControllerPath;
		} else {			
			//Здесь нужно добавить обработку ошибки.
			//Например, перекинуть пользователя на страницу 404
			return false;
		}
		
		$controller = new $controllerClassName($data);
		$action = 'action_' . $method;
		
		if (method_exists($controller, $action)){
			//Удаляем имя метода из массива
			array_shift($pieces);
			call_user_func(array($controller, $action), $pieces);
		} else {
			// Не нашли указанного метода. Передадим оставшиеся части как параметр
			$action = 'action_index';
			
			if (method_exists($controller, $action)) {
				call_user_func(array($controller, $action), $pieces);
			} else {
				return false;
			}
		}
	}	
}
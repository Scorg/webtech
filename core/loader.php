<?php

class Loader {
	protected $registry;
	
	public function __construct($registry)
	{
		$this->registry = $registry;
	}
	
	public function __get($key)
	{
		return $this->registry[$key];
	}
	
	public function __set($key, $value)
	{
		$this->registry[$key] = $value;
	}
	
	public function model($model)
	{
		$file = MODELS_ROOT . $model . '.php';
		
		if (file_exists($file)) {
			$pos = strrpos($model, '/');
			$class = 'Model' . substr($model, ($pos!==false ? $pos+1 : 0));
			
			require_once($file);
			
			if (class_exists($class))
				return new $class($this->registry);
		}
		
		trigger_error('Error: Could not load model ' . $file . '!');
		exit();		
	}
		
	public function view($template, $data = array())
	{
		$file = TEMPLATES_ROOT . $template;

		if (file_exists($file)) {
			extract($data);

			ob_start();

			require($file);

			$output = ob_get_contents();

			ob_end_clean();
		} else {
			trigger_error('Error: Could not load template ' . $file . '!');
			exit();
		}

		return $output;
	}
}
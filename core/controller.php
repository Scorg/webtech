<?php

abstract class Controller {
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
	
	public function render($template, $data = array()) {
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
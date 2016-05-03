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
	
	public function render($template, $data = array()) 
	{
		return $this->load->view($template, $data);
	}
}
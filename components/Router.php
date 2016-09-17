<?php

class Router
{
	
	private $routes;
	
	public function __construct()
	{
		$routesPath = ROOT.'/config/routes.php';
		$this->routes = include($routesPath);
	}
	
	/*
	* Returns request string
	*/
	private function getUri()
	{
		if (!empty($_SERVER['REQUEST_URI'])) {
			return trim($_SERVER['REQUEST_URI'], '/');
		}
	}
	
	public function run()
	{		
		//получение строки запроса
		$uri = $this->getUri();
		
		//проверяем наличие запроса в routes.php
		foreach ($this->routes as $uriPattern => $path) {
			if (preg_match("~$uriPattern~",$uri)) {
				$segments = explode('/', $path);
				
				$controllerName = ucfirst(array_shift($segments).'Controller');
				
				$actionName = 'action'.ucfirst(array_shift($segments));
				
				//подключаем файл класса контроллера
				$controllerFile = ROOT.'/controllers/'.$controllerName.'.php';
				
				if (file_exists($controllerFile)) {
					include_once($controllerFile);
					
				
				}
				
				//создаем объект и вызываем метод
				$controllerObject = new $controllerName;
				$result = $controllerObject->$actionName();
				if ($result != null) {
					break;
				}
				
			}
		}
		 
	}
	
}






















<?php

use \Rapd\Router\Route;
use \Rapd\View;

function route(string $name, array $data =  []){
	return Router::makeUrlTo($name, $data);
}

function render(string $name, array $data = []){
	return View::render($name, $data);
}

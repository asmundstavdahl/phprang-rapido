<?php

use \Rapd\Router\Route;

function route(string $name, array $data){
	return Route::to($name, $data);
}

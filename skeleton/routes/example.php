<?php

use \Rapd\Router;
use \Rapd\View;

#     route name, regex, callback
Router::get("home", "/", function(){
	return View::render("home");
});
Router::get("hello_world", "/world", [\HelloController::class, "world"]);
Router::get("hello_something", "/(\w+)", [\HelloController::class, "something"]);

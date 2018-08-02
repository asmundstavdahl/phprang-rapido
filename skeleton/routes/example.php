<?php

use \Rapd\Router;
use \Rapd\View;

Router::get("home", "/", function(){
	return View::render("home");
});
Router::get("hello_world", "/world", [\HelloController::class, "world"]);
Router::get("just_hello", "/hello", [\HelloController::class, "justHello"]);
Router::get("hello_something", "/(\w+)", [\HelloController::class, "something"]);

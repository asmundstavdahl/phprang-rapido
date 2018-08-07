<?php

use \Rapd\Router;
use \Rapd\Router\Route;
use \Rapd\View;

Router::add(new Route(
	"home",
	"/",
	function(){
		return View::render("home");
	}
));
Router::add(new Route(
	"hello_world",
	"/world",
	[\HelloController::class,"world"]
));
Router::add(new Route(
	"just_hello",
	"/hello",
	[\HelloController::class,"justHello"]
));
Router::add(new Route(
	"hello_something",
	"/(\w+)",
	[\HelloController::class,"something"]
));

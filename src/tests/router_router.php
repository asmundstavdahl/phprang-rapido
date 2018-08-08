<?php

use \Rapd\Router;
use \Rapd\Router\Route;

$homeRoute = new Route(
	"home",
	"/",
	function(){
		return "Home";
	}
);
assert($homeRoute->match("/") == true);
assert($homeRoute->name == "home");

Router::add($homeRoute);
assert(Router::getRouteByName("home")->name == "home");
assert(Router::getRouteByName("home")->match("/"));
assert(Router::makeUrlTo("home") == "/");

$route = Router::match("/");
assert(Route::class == get_class($route));
assert("Home" == $route->execute("/"));

Router::add(new Route(
	"profile",
	"/user/(\d+)",
	function(int $id){
		return "User {$id}";
	}
));
assert(false === Router::match("/user/me"));
assert(false === Router::match("/user/"));
assert("profile" == Router::match("/user/42")->name);
assert("User 42" == Router::match("/user/42")->execute("/user/42"));
assert(Router::makeUrlTo("profile", [1337]) == "/user/1337");

Router::add(new Route(
	"article",
	"/(\d+)",
	function(int $id){
		return "Article {$id}";
	}
));
Router::setBasePath("/article");
assert(false === Router::match("/article/me"));
assert(false === Router::match("/me"));
assert("article" == Router::match("/42")->name);
assert("Article 42" == Router::match("/42")->execute("/42"));
assert(Router::makeUrlTo("home", []) == "/article/");
assert(Router::makeUrlTo("article", [1337]) == "/article/1337");

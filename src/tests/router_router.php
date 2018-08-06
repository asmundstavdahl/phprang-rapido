<?php

use \Rapd\Router;

# Adding route, getting routes and resetting router
assert(count(Router::getAllRoutes()) == 0);
Router::get("tmp", "/tmp", function(){ return "tmp out"; });
assert(count(Router::getAllRoutes()) == 1);
assert(Router::getAllRoutes()["tmp"]->name == "tmp");
Router::reset();
assert(count(Router::getAllRoutes()) == 0);

# Route matching
Router::get("home", "/", function(){
	return "hei";
});
$route = Router::findMatchingRoute("GET", "/");
$output = Router::run("GET", "/");
assert($output == "hei");
Router::reset();

# makeUrl
Router::get("show_article", "/(\d+)", function(int $id){
	return "Article {$id}";
});
$route = Router::findMatchingRoute("GET", "/123");
assert($route->makeUrl([4321]) == "/4321");
$output = Router::run("GET", $route->makeUrl([987]));
assert($output == "Article 987");

# makeUrl and base application path
Router::setApplicationBasePath("/app");
assert("/42" == Router::makeUrlTo("show_article", [42]));
Router::setApplicationBasePath("/app/");
assert("/43" == Router::makeUrlTo("show_article", [43]));

Router::reset();
Router::loadDirectory(__DIR__."/_testRoutes");
assert(Router::run("GET", "/GET") == "GET");
assert(Router::run("POST", "/POST") == "POST");
assert(Router::run("PUT", "/PUT") == "PUT");
assert(Router::run("DELETE", "/DELETE") == "DELETE");
Router::reset();

<?php

use \Rapd\Router\Route;

$route = new Route("home", "/", "GET", function(){
	return "hello";
});
# Constructor
assert($route->name == "home");
assert($route->pattern == "/");
assert($route->method == "GET");

assert($route->match("GET", "/") == true);
assert(count($route->patternMatches) == 1);
assert($route->patternMatches[0] == "/");
$output = $route->execute();
assert($output == "hello");
assert($route->match("GET", "/away") == false);
assert(count($route->patternMatches) == 0);
assert($route->match("POST", "/") == false);
assert(count($route->patternMatches) == 0);

$route = new Route("profile", "/user/(\d+)", "GET", function(int $id){
	return "User {$id}";
});
assert($route->makeUrl([42]) == "/user/42");

<?php

namespace Rapd;

use \Rapd\Environment;

class Router {
	use Prototype;

	private static $applicationBasePath = "/";
	private static $routes = [];

	public static function setApplicationBasePath(string $applicationBasePath){
		self::$applicationBasePath = $applicationBasePath;
	}
	public static function getApplicationBasePath() : string {
		return self::$applicationBasePath;
	}

	public static function findRouteByName(string $name) {
		foreach(self::$routes as $route){
			if($route->name == $name){
				return $route;
			}
		}
		return null;
	}

	public static function routeTo(string $name, array $data = []){
		$route = self::findRouteByName($name);
		if($route){
			return $route->makeURL($data);
		} else {
			error_log("Route '{$name}' is not registered. Got a ".gettype($route)." from the Router.");
			error_log("Registered routes are:");
			foreach(self::$routes as $route){
				error_log("    {$route->name} – ".join("::", $route->callback));
			}
			return "#no-such-route:{$name}";
		}
	}

	public static function loadDirectory(string $dir){
		foreach(glob("{$dir}/*.php") as $routeFile){
			include_once $routeFile;
		}
	}

	public static function get(string $name, string $pattern, $callback = null){
		self::registerRoute("GET", $name, $pattern, $callback);
	}

	public static function post(string $name, string $pattern, $callback = null){
		self::registerRoute("POST", $name, $pattern, $callback);
	}

	public static function registerRoute(string $method, string $name, string $pattern, $callback){
		$route = new Router\Route(
			$name,
			$pattern,
			$method,
			$callback
		);
		$route->applicationBasePath = self::$applicationBasePath;
		self::$routes[$name] = $route;
	}

	public static function run(){
		foreach(self::sortRoutes(self::$routes) as $route){
			if($route->match()){
				error_log("Route: {$route->name}");
				return $route->execute();
			}
		}

		return false;
	}

	public static function getAllRoutes() : array {
		return self::$routes;
	}

	public static function redirectTo(string $name, array $data = []){
		header("Location: ".self::routeTo($name, $data));
		exit;
	}

	private static function sortRoutes(array $routes){
		usort($routes, function(Router\Route $a, Router\Route $b){
			return substr_count($a->pattern, "/") < substr_count($b->pattern, "/");
		});
		return $routes;
	}
}

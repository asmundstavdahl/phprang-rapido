<?php

namespace Rapd;

use \Rapd\Environment;
use \Rapd\Router\Route;

class Router {
	use Prototype;

	private static $applicationBasePath = "/";
	protected static $routes = [];

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

	public static function makeUrlTo(string $name, array $data = []){
		$route = self::findRouteByName($name);
		if($route){
			return $route->makeUrl($data);
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
	
	public static function put(string $name, string $pattern, $callback = null){
		self::registerRoute("PUT", $name, $pattern, $callback);
	}

	public static function delete(string $name, string $pattern, $callback = null){
		self::registerRoute("DELETE", $name, $pattern, $callback);
	}

	public static function registerRoute(string $method, string $name, string $pattern, $callback){
		$route = new Route(
			$name,
			$pattern,
			$method,
			$callback
		);
		self::addRoute($route);
	}
	public static function addRoute(Route $route){
		self::$routes[$route->name] = $route;
	}

	public static function run($method = null, $uri = null){
		$method = $method !== null ?$method :$_SERVER["REQUEST_METHOD"];
		$uri = $uri !== null ?$uri :$_SERVER["REQUEST_URI"];

		$matchingRoute = self::findMatchingRoute($method, $uri);

		if($matchingRoute){
			return $matchingRoute->execute();
		}
		return false;
	}

	public static function findMatchingRoute(string $method, string $uri){
		foreach(self::sortRoutes(self::$routes) as $route){
			$uri = $uri; # $_SERVER["REQUEST_URI"]
			if($route->match($method, $uri)){ # $_SERVER["REQUEST_METHOD"]
				return $route;
			}
		}

		return false;
	}

	public static function getAllRoutes() : array {
		return self::$routes;
	}

	public static function redirectTo(string $name, array $data = []){
		header("Location: ".self::makeUrlTo($name, $data));
		exit;
	}

	private static function sortRoutes(array $routes){
		usort($routes, function(Route $a, Route $b){
			return substr_count($a->pattern, "/") < substr_count($b->pattern, "/");
		});
		return $routes;
	}

	public static function reset(){
		self::$applicationBasePath = "/";
		self::$routes = [];
	}
}

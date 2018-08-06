<?php

namespace Rapd\Router;

use \Rapd\Router;

class Route {
	use \Rapd\Prototype;

	public $name = "";
	public $pattern = "#";
	public $method = "GET";
	public $callback = [Route::class, "youDeserveThisError"];
	public $patternMatches = [];
	public $baseURL = "/";

	function __construct(string $name, string $pattern, string $method, $callback){
		$this->name = $name;
		$this->pattern = $pattern;
		$this->method = $method;
		$this->callback = $callback;
	}

	public function match(){
		if($_SERVER["REQUEST_METHOD"] == $this->method){
			$uri = str_replace(Router::getApplicationBasePath(), "", $_SERVER["REQUEST_URI"]);
			# It's important to keep the first slash here
			$uri = str_replace("//", "/", "/{$uri}");

			$regex = "`^{$this->pattern}$`";
			$matches = [];
			if(preg_match($regex, $uri, $matches)){
				$this->patternMatches = $matches;
				return true;
			}
		}
		return false;
	}

	public function execute(){
		if(count($this->patternMatches) > 0){
			array_shift($this->patternMatches);
			return call_user_func_array(
				$this->callback,
				$this->patternMatches
			);
		}
	}

	public function makeUrl(array $data){
		$path = $this->pattern;
		foreach($data as $key => $value){
			$path = preg_replace("/\([^)]*\)/", $value, $path, 1);
		}
		return Router::getApplicationBasePath().str_replace("//", "/", "{$this->baseURL}{$path}");
	}
}

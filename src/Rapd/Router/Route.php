<?php

namespace Rapd\Router;

use \Rapd\Router;

class Route {
	use \Rapd\Prototype;

	public $name = "";
	public $pattern = "#";
	public $method = "GET";
	public $callback = [Your::class, "yourMethodHere"];

	# Will be populated by ->match()
	public $patternMatches = [];

	function __construct(string $name, string $pattern, string $method, $callback){
		$this->name = $name;
		$this->pattern = $pattern;
		$this->method = $method;
		$this->callback = $callback;
	}

	public function match(string $method, string $uri){
		if($method == $this->method){
			$regex = "`^{$this->pattern}$`";
			$matches = [];
			if(preg_match($regex, $uri, $matches)){
				$this->patternMatches = $matches;
				return true;
			}
		}
		$this->patternMatches = [];
		return false;
	}

	public function execute(){
		if(count($this->patternMatches) > 0){
			array_shift($this->patternMatches);
			return call_user_func_array(
				$this->callback,
				$this->patternMatches
			);
		} else {
			throw new Exception("Match the route to a URI first");
		}
	}

	public function makeUrl(array $data){
		$path = $this->pattern;
		foreach($data as $key => $value){
			$path = preg_replace("/\([^)]*\)/", $value, $path, 1);
		}
		return $path;
	}
}

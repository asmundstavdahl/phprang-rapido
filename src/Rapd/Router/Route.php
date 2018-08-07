<?php

namespace Rapd\Router;

use \Rapd\Router;

class Route {
	use \Rapd\Prototype;

	public $name = "some_re";
	public $pattern = "/some/([rR]eg[eE]xp?)";
	public $callback = [Your::class, "yourMethodHere"];

	function __construct(string $name, string $pattern, $callback){
		$this->name = $name;
		$this->pattern = $pattern;
		$this->callback = $callback;
	}

	public function match(string $uri) : bool {
		return count($this->matchPattern($uri)) > 0;
	}

	public function execute(string $uri){
		if($this->match($uri)){
			$parameters = $this->matchPattern($uri);
			# Remove the whole-pattern-match
			array_shift($parameters);
			return call_user_func_array(
				$this->callback,
				$parameters
			);
		} else {
			throw new Exception("URI does not match route's pattern");
		}
	}

	public function makeUrl(array $data) : string {
		$path = $this->pattern;
		foreach($data as $key => $value){
			$path = preg_replace("/\([^)]*\)/", $value, $path, 1);
		}
		return $path;
	}

	private function matchPattern(string $uri) : array {
		$regex = "`^{$this->pattern}$`";
		$matches = [];
		preg_match($regex, $uri, $matches);
		return $matches;
	}
}

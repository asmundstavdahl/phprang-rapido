<?php

namespace Rapd;

class Environment {
	use Prototype;

	private static $props = [];

	public static function supply(string $name, $what){
		self::$props[$name] = $what;
	}

	public static function get(string $name){
		return self::$props[$name];
	}

	public static function everything(){
		return self::$props;
	}
}

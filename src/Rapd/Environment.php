<?php

namespace Rapd;

class Environment {
	use Prototype;

	private static $props = [];

	public static function set(string $name, $what){
		self::$props[$name] = $what;
	}

	public static function get(string $name){
		return self::$props[$name];
	}

	public static function getAll(){
		return self::$props;
	}
}

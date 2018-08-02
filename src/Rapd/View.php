<?php

namespace Rapd;

class View {
	use Prototype;

	private static $renderer = [self::class, "echo"];

	public static function setRenderer(callable $renderer){
		self::$renderer = $renderer;
	}

	public static function render(string $name, array $data = []){
		return call_user_func_array(self::$renderer, [$name, $data]);
	}

	public static function echo(string $template, array $data){
		return "{$template}:\n".print_r($data, true);
	}
}

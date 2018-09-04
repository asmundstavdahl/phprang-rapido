<?php

namespace Rapd;

/**
 * Slap this trait on an unfinished class.
 */
trait Prototype {
	function __call($method, $args){
		$argString = self::describeArray($args);
		$message = "TODO: implement ".get_called_class()."->{$method}({$argString})";
		error_log($message);
		echo "{$message} <br>\n";
	}

	public static function __callStatic($method, $args){
		$argString = self::describeArray($args);
		$message = "TODO: implement ".get_called_class()."::{$method}({$argString})";
		error_log($message);
		echo "{$message} <br>\n";
	}

	/**
	 * In: ["abc", 42, new Class()]
	 * Out: "string, integer, object"
	 */
	private static function describeArray(array $arr){
		return implode(
			", ",
			array_map(function($item){
				return gettype($item)
					.":"
					.substr(
						print_r($item, true),
						0, 10
					);
			}, $arr));
	}
}

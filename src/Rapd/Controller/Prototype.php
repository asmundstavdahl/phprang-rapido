<?php

namespace Rapd\Controller;

/**
 * Slap this trait on an unfinished class.
 */
trait Prototype {
	use \Rapd\Prototype;

	public static function __callStatic($method, $args){
		$argString = self::describeArray($args);
		$message = "TODO: implement ".self::class."::{$method}({$argString})";
		return $message;
	}
}

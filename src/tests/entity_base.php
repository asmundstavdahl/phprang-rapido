<?php

use \Rapd\BaseEntity;

class FieldValidator extends BaseEntity {
	protected static $fields = [
		"id" => integer::class,
		"name" => string::class,
		"height" => float::class,
		"blob" => "some other type",
	];

	public function VALIDATE_id($value){
		return $value > 0;
	}
	public function VALIDATE_name($value){
		return strlen($value) >= 1;
	}
	public function VALIDATE_blob($value){
		return strlen($value) > 1;
	}
}

$fv = new FieldValidator();

assert(true === $fv->validateBuiltinType(integer::class, 4321, false));
assert(true === $fv->validateBuiltinType(integer::class, -4321, false));
assert(false === $fv->validateBuiltinType(integer::class, 432.1, false));
assert(false === $fv->validateBuiltinType(integer::class, "vffdsfd", false));

$ex = null;
try {
	$fv->id = 0;
} catch(Exception $e) {
	$ex = $e;
}
assert($ex !== null);

$ex = null;
try {
	$fv->id = 1;
} catch(Exception $e) {
	$ex = $e;
}
assert($ex === null);
assert($fv->id === 1);

$ex = null;
try {
	$fv->blob = "";
} catch(Exception $e) {
	$ex = $e;
}
assert($ex !== null);


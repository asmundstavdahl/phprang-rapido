<?php

use \Rapd\BaseEntity;

class FieldValidator extends BaseEntity {
	protected static $fields = [
		"id" => integer::class,
		"name" => string::class,
		"height" => float::class,
		"description" => "some other type",
	];

	public function VALIDATE_id($value){
		return $value > 0;
	}
	public function VALIDATE_name($value){
		return strlen($value) >= 1;
	}
	public function VALIDATE_description($value){
		return strlen($value) > 1;
	}
}

$fv = new FieldValidator();

# Builtin type validator tests
assert(true === $fv->validateBuiltinType(integer::class, 4321, false));
assert(true === $fv->validateBuiltinType(integer::class, -4321, false));
assert(true === $fv->validateBuiltinType(integer::class, "4321", false));
assert(false === $fv->validateBuiltinType(integer::class, 432.1, false));
assert(false === $fv->validateBuiltinType(integer::class, "432.1", false));
assert(false === $fv->validateBuiltinType(integer::class, "vffdsfd", false));
assert(false === $fv->validateBuiltinType(integer::class, $fv, false));

assert(true === $fv->validateBuiltinType(float::class, 4321.1, false));
assert(true === $fv->validateBuiltinType(float::class, -4321.1, false));
assert(true === $fv->validateBuiltinType(float::class, 4321, false));
assert(true === $fv->validateBuiltinType(float::class, "4321", false));
assert(true === $fv->validateBuiltinType(float::class, "-4321.1", false));
assert(false === $fv->validateBuiltinType(float::class, "vffdsfd", false));
assert(false === $fv->validateBuiltinType(float::class, $fv, false));

assert(true === $fv->validateBuiltinType(string::class, "fus ro dah", false));
assert(true === $fv->validateBuiltinType(string::class, "4321.1", false));
assert(true === $fv->validateBuiltinType(string::class, "", false));
assert(false === $fv->validateBuiltinType(string::class, null, false));
assert(false === $fv->validateBuiltinType(string::class, 4321, false));
assert(false === $fv->validateBuiltinType(string::class, 4321.1, false));
assert(false === $fv->validateBuiltinType(string::class, $fv, false));

# Setting invalid value throws
$ex = null;
try {
	$fv->id = 0;
} catch(Exception $e) {
	$ex = $e;
}
assert($ex !== null);

# Setting valid values changes value
$ex = null;
try {
	$fv->id = 1;
} catch(Exception $e) {
	$ex = $e;
}
assert($ex === null);
assert($fv->id === 1);

# Float validator accepts ints, sets value
$ex = null;
try {
	$fv->height = -1234;
} catch(Exception $e) {
	$ex = $e;
}
assert($ex === null);
assert($fv->height == -1234);

# Float validator throws
$ex = null;
try {
	$fv->height = "very high";
} catch(Exception $e) {
	$ex = $e;
}
assert($ex !== null);
assert($fv->height != "very high");

# Validator for non-builtin type throws only on error
$ex = null;
try {
	$fv->description = "";
} catch(Exception $e) {
	$ex = $e;
}
assert($ex !== null);

$ex = null;
try {
	$fv->description = "An actual description";
} catch(Exception $e) {
	$ex = $e;
}
assert($ex === null);

# Constructor sets values
$ex = null;
try {
	$fv = new FieldValidator([
		"id" => 2,
		"name" => "Mitt Navn",
		"height" => 1.23,
		"description" => "Test validator",
	]);
} catch(Exception $e) {
	$ex = $e;
}
assert($ex === null);

assert($fv->id === 2);
assert($fv->name === "Mitt Navn");
assert($fv->height === 1.23);
assert($fv->description === "Test validator");

# Patch changes values
$ex = null;
try {
	$fv->patch([
		"id" => 3,
		"name" => "Nytt Navn",
		"height" => 2.34,
		"description" => "Path test",
	]);
} catch(Exception $e) {
	$ex = $e;
}
assert($ex === null);

assert($fv->id === 3);
assert($fv->name === "Nytt Navn");
assert($fv->height === 2.34);
assert($fv->description === "Path test");

# Patch validates and throws
$ex = null;
try {
	$fv->patch([
		"id" => -1,
	]);
} catch(Exception $e) {
	$ex = $e;
}
assert($ex !== null);

<?php

require __DIR__."/_namespacedEntity.php";

use \Rapd\PersistableEntity;
use \Rapd\Database;

class Foo extends PersistableEntity {}
class TestEntity1 extends PersistableEntity {}
class TestNumberTwo extends PersistableEntity {}

assert(Foo::getTable() == "foo");
assert(TestEntity1::getTable() == "test_entity_1");
assert(TestNumberTwo::getTable() == "test_number_two");
assert(Test\ThreeEntity::getTable() == "three_entity");

# Database setup
Database::$pdo = new PDO("sqlite:".__DIR__."/test.sqlite3");
Database::$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
try {
	Database::$pdo->exec('DROP TABLE bar;');
} finally {
	Database::$pdo->exec('
		CREATE TABLE bar (
			"id" INTEGER PRIMARY KEY,
			"name" TEXT
		);
	');
}

# Test entity
class Bar extends PersistableEntity {
	static $fields = [
		"id" => integer::class,
		"name" => string::class,
	];

	function VALIDATE_name($value){
		return strlen($value) > 0;
	}
}

# Validation works
$ex = null;
try {
	new Bar(["name" => ""]);
} catch(Exception $e){
	$ex = $e;
}
assert($ex !== null);

$ex = null;
try {
	new Bar(["name" => "a"]);
} catch(Exception $e){
	$ex = $e;
}
assert($ex === null);

# Insert returns new ID
$bar0 = new Bar(["name" => "Bar0"]);
$id = $bar0->insert();
assert($bar0->id > 0);
assert($id == $bar0->id);
$bar0->delete();
$bars = Bar::findAll();
assert(count($bars) == 0, "Want 0, have ".count($bars));

# Inserting and finding work together
$bar1 = new Bar(["name" => "Bar A"]);
$bar1->insert();
$bar1_ = Bar::findById($bar1->id);
assert(get_class($bar1_) == Bar::class);
assert($bar1_->id == $bar1->id);
assert($bar1_->name == $bar1->name);

# Updating changes existing row
$bar1_->name = "Bar B";
$bar1_->update();
$bars = Bar::findAll();
assert(count($bars) == 1);
assert($bars[0]->name == "Bar B");

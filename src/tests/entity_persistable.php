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

class Bar extends PersistableEntity {
	static $fields = [
		"id" => integer::class,
		"name" => string::class,
	];

	function VALIDATE_name($value){
		return strlen($value) > 0;
	}
}

$bar = new Bar([
	"name" => "Bar A",
]);
$bar->insert();

$bar_ = Bar::findById(1);
assert($bar_->name == $bar->name);

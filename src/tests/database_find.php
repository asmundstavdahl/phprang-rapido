<?php

use \Rapd\PersistableEntity;
use \Rapd\Database;

# Database setup
Database::$pdo = new PDO("sqlite::memory:");
Database::$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
Database::$pdo->exec('
	CREATE TABLE find_me (
		"id" INTEGER PRIMARY KEY,
		"name" TEXT
	);
');

class FindMe extends PersistableEntity {
	static $fields = [
		"id" => integer::class,
		"name" => string::class,
	];
}

$fm1 = new FindMe(["name" => "one"]);
$fm1->insert();
$fm2 = new FindMe(["name" => "two"]);
$fm2->insert();
$fm3 = new FindMe(["name" => "three"]);
$fm3->insert();

$fms = FindMe::findAllWhere("name = 'one'");
assert(count($fms) == 1);
assert($fms[0]->id == 1);
$fms = FindMe::findAllWhere("name LIKE 't%'");
assert(count($fms) == 2);
assert($fms[0]->id == 2);
$fms = FindMe::findAllWhere("name LIKE :search", [":search" => "o%"]);
assert(count($fms) == 1);
assert($fms[0]->id == 1);

$fm = FindMe::findFirstWhere("name = 'three'");
assert($fm->id == 3);
assert($fm->name == "three");
$fm = FindMe::findFirstWhere("name LIKE 't%'");
assert($fm->id == 2);
assert($fm->name == "two");
$fm = FindMe::findFirstWhere("name LIKE :q", [":q" => "%e"]);
assert($fm->id == 1);
assert($fm->name == "one");

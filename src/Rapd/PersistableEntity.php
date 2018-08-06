<?php

namespace Rapd;

class PersistableEntity extends BaseEntity {
	use Prototype;

	/**
	 * If not overridden this method will imply a table name.
	 * Examples (entity class => table name):
	 *     TodoTask => todo_task
	 *     App\Namespace\City => city
	 * @see  tests/entity_tests.php
	 */
	public static function getTable() : string {
		$namespacedClassParts = explode("\\", get_called_class());
		$table = array_pop($namespacedClassParts);
		$table = preg_replace("/([a-z0-9])([A-Z0-9])/", '$1_$2', $table);
		$table = strtolower($table);
		return $table;
	}

	public function insert(){
		return Database::insert($this);
	}

	public function update(){
		return Database::update($this);
	}

	public function delete(){
		return Database::delete($this);
	}

	public static function findById(int $id){
		return Database::findById(get_called_class(), $id);
	}

	public static function findAll(){
		return Database::findAll(get_called_class());
	}

	public static function findFirstWhere(string $condition, array $binds = []){
		return Database::findFirstWhere(get_called_class(), $condition, $binds);
	}

	public static function findAllWhere(string $condition, array $binds = []){
		return Database::findAllWhere(get_called_class(), $condition, $binds);
	}
}

<?php

namespace Rapd;

class PersistableEntity extends BaseEntity {
	use Prototype;

	private $data = [];

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

	public function findById(int $id){
		return Database::findById(get_called_class(), $id);
	}

	public function findAll(){
		return Database::findAll(get_called_class());
	}
}

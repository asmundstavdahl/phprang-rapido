<?php

namespace Rapd;

class Entity {
	use Prototype;

	private $data = [];

	# Override me
	protected static $columns = [
		"id" => integer::class
	];

	public function __construct(array $data = []){
		$defaultValues = [
			integer::class => "0",
			string::class => "",
		];
		foreach(get_called_class()::$columns as $column => $type){
			if(!array_key_exists($column, $this->data)){
				switch($type){
					case integer::class:
					case string::class:
						$this->{$column} = $defaultValues[$type];
						break;
					default:
						$this->{$column} = null;
				}
			}
		}
		$this->patch($data);
	}

	public static function getTable() : string {
		$namespacedClassParts = explode("\\", get_called_class());
		$table = array_pop($namespacedClassParts);
		$table = preg_replace("/([a-z0-9])([A-Z])/", '$1_$2', $table);
		$table = strtolower($table);
		return $table;
	}

	public static function getColumns() : array {
		return array_keys(get_called_class()::$columns);
	}

	public function patch(array $data){
		foreach($data as $key => $value){
			$this->__set($key, $value);
		}
	}

	public function __set($key, $value){
		$validationMethod = "VALIDATE_{$key}";
		$classMethods = get_class_methods(get_called_class());

		if(array_search($validationMethod, $classMethods)){
			$isValid = $this->{$validationMethod}($value);

			if(!$isValid){
				throw new Exception(
					"Invalid value for ".get_called_class()."->{$key}"
				);
			}
		}

		$this->data[$key] = $value;
	}

	public function __get($key){
		return $this->data[$key];
	}
}

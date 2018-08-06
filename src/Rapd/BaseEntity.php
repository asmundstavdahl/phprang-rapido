<?php

namespace Rapd;

/**
 * Basic entity, without any persistance features.
 */
class BaseEntity {
	use Prototype;

	private $values = [];

	/**
	 * Override this array, which describes the fields of the extending entity class.
	 * Format is:
	 *     "field_name" => (integer|text|float|...)::class
	 * @var array
	 */
	protected static $fields = [
		"id" => \integer::class
	];

	public function __construct(array $values = []){
		$this->values = array_map(function($fieldType){
			return get_called_class()::getDefaultValueForType($fieldType);
		}, $this->getFields());
		$this->patch($values);
	}

	protected static function getDefaultValueForType(string $type){
		switch($type){
			case \integer::class: return  0;
			case \string::class: return  "";
			case \float::class: return  0.0;
		}
		return null;
	}

	public function patch(array $values){
		foreach($values as $key => $value){
			if(array_key_exists($key, $this->getFields())){
				if($this->validateFieldValue($key, $value)){
					$this->__set($key, $value);
				}
			} else {
				error_log("Rejecting {$key} because it is not in the field list of ".get_called_class());
			}
		}
	}

	public function validateFieldValue($field, $value, $throwOnInvalidValue = true){
		$validationMethod = "VALIDATE_{$field}";
		$validationMethodExists = false !== array_search($validationMethod, get_class_methods(get_called_class()));

		$isValid = $this->validateBuiltinType($this->getFields()[$field], $value);

		# If the basic type validators above approves the value,
		# also ask the field-specific validator if it exists.
		if($isValid && $validationMethodExists){
			$isValid = call_user_func([get_called_class(), $validationMethod], $value);
		} else {
			#echo "Didn't find {$validationMethod} for ".get_called_class()."\n";
		}

		if($throwOnInvalidValue && !$isValid){
			throw new \Exception("Field validation failed for {$field}");
		}

		return $isValid;
	}

	public static function validateBuiltinType(string $type, $value) : bool {
		switch($type){
			case \integer::class:
				return is_numeric($value) && ceil($value) == floor($value);
			case \string::class:
				return is_string($value);
			case \float::class:
				return is_numeric($value);
		}
		return true;
	}

	public function __set($key, $value){
		if($this->validateFieldValue($key, $value)){
			$this->values[$key] = $value;
		}
	}

	public function __get($key){
		return $this->values[$key];
	}

	public static function getFields() : array {
		return get_called_class()::$fields;
	}
}

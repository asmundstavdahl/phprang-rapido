<?php

class Task extends \Rapd\Entity {
	static $columns = [
		"id" => integer::class,
		"body" => string::class
	];

	public function VALIDATE_body($body){
		error_log("Hei");
		return strlen($body) > 1;
	}
}

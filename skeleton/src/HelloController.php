<?php

use \Rapd\View;
use \Rapd\Environment;

class HelloController {
	use \Rapd\Controller\Prototype;

	public function justHello(){
		return "Hello.";
	}
	
	public function something(string $word){
		Environment::set("TITLE", "{$word}!!!");
		return View::render("hello", [
			"word" => $word
		]);
	}
}

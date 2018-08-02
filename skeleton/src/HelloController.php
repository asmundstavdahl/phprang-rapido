<?php

use \Rapd\View;

class HelloController {
	use \Rapd\Controller\Prototype;

	public function something(string $word){
		return View::render("hello", [
			"word" => $word
		]);
	}
}

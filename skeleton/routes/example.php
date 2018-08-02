<?php

use \Rapd\Router;

Router::get("hello", "/hello", function(){
	return View::render("hello", ["world"]);
});
# \WordController::show()
Router::get("word_show", "/word/(\w+)");
# \WordController::showLetter()
Router::get("show_letter_in_word", "/word/(\w+)/(\w)", [WordController::class, "showLetter"]);

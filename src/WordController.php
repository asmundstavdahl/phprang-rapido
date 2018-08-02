<?php

class WordController {
	use \Rapd\Controller\Prototype;

	public static function show(string $word){
		return "The word is <b>{$word}</b>.";
	}

	public function showLetter(string $word, string $letter){
		return str_replace($letter, "<b>{$letter}</b>", $word);
	}
}

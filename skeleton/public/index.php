<?php

# Maybe autoload composer packages too
if(file_exists("../vendor/autoload.php")){
    require "../vendor/autoload.php";
}

require "../src/autoload.php";

use \Rapd\Router;
use \Rapd\View;
use \Rapd\Environment;

View::setRenderer(function(string $template, array $data = []){
	require_once "../src/template-preparations.php";
	extract(Environment::getAll());
	extract($data);
	include "../templates/header.php";
	include "../templates/{$template}.php";
	include "../templates/footer.php";
});

Router::setBaseURL("/");
Router::loadDirectory("../routes");

$result = Router::run();

if($result === false){
	header('HTTP/1.1 404 Not Found');
	echo View::render("404");
} else {
	echo $result;
}

<?php

# Maybe autoload composer packages too
if(file_exists("../vendor/autoload.php")){
    require "../vendor/autoload.php";
}

require "../src/autoload.php";

use \Rapd\Router;
use \Rapd\View;

View::setRenderer(function(string $template, array $data = []){
	require_once "../src/template-preparations.php";
	extract($data);
	include "../templates/{$template}.php";
});

Router::setBaseURL("/");
Router::loadDirectory("../routes");

$output = Router::run();

echo $output;

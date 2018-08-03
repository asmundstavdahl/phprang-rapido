<?php

# Maybe autoload composer packages too
if(file_exists("../vendor/autoload.php")){
    require "../vendor/autoload.php";
}

require "../src/autoload.php";

use \Rapd\Router;
use \Rapd\View;
use \Rapd\Environment;

# Configure a function to be used by View::render()
View::setRenderer(function(string $template, array $data = []){
	require_once "../src/template-preparations.php";
	extract(Environment::getAll());
	extract($data);
	include "../templates/{$template}.php";
});

# The router's application base path defaults to "/".
#Router::setApplicationBasePath("/myproject");
#Router::setApplicationBasePath(str_replace($_SERVER["DOCUMENT_ROOT"], "", __DIR__));
# Include PHP files from the directory
Router::loadDirectory("../routes");

# Set some environment variables used by the header template
Environment::set("TITLE", "rapd/skeleton");
Environment::set("AUTHOR", "Åsmund Stavdahl");
Environment::set("DESCRIPTION", "Default description of the rapd/skeleton");

# Generate a response based on the request and the configured routes
$result = Router::run();

# Send the response
if($result === false){
	header('HTTP/1.1 404 Not Found');
	echo View::render("404");
} else {
	echo $result;
}

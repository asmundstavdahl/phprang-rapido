<?php

use \Rapd\Router;

$callback = function($arg){ return $arg; };
Router::get("GET", "/(.*)", $callback);
Router::post("POST", "/(.*)", $callback);
Router::put("PUT", "/(.*)", $callback);
Router::delete("DELETE", "/(.*)", $callback);

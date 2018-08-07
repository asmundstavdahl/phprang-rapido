<?php

use \Rapd\Router;

# getAppPathFromAbsoluteUri
Router::setApplicationBasePath("/");
assert("/1/2" == Router::getPathFromUri("/1/2"));
Router::setApplicationBasePath("");
assert("/1/2" == Router::getPathFromUri("/1/2"));
Router::setApplicationBasePath("/1");
assert("/2" == Router::getPathFromUri("/1/2"));
Router::setApplicationBasePath("/1/");
assert("/2" == Router::getPathFromUri("/1/2"));
Router::setApplicationBasePath("/1/");
assert("/2" == Router::getPathFromUri("/1/2/"));

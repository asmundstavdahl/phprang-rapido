<?php
/**
 * This files registers a simple PSR-4 autoload function.
 * 
 * Classes will be loaded by path relative to this file's location based on the
 * class' namespace and name.
 * 
 * Example 1:
 * "TimeProject" file structure:
 * /autoload.php <- this file
 * /index.php <- includes the above autoload.php
 * /Clock.php
 *      `-> autoloaded for class \Clock
 * /Calendar/Day.php
 *      `-> autoloaded for class \Calendar\Day
 * 
 * Example 2:
 * "TidyTimeProject" file structure:
 * /src/autoload.php <- this file
 * /index.php <- includes the above autoload.php
 * /src/Clock.php
 *      `-> autoloaded for class \Clock
 * /src/Calendar/Day.php
 *      `-> autoloaded for class \Calendar\Day
 */

/**
 * PSR-4 autoload
 */
spl_autoload_register(function($class) {
    $file = __DIR__."/".str_replace('\\', '/', $class).'.php';
    if(file_exists($file)){
        require $file;
    } else {
        #echo "Class file not found: $file";
    }
});

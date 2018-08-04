<?php

require __DIR__."/../autoload.php";

if(file_exists(__DIR__."/../../vendor/autoload.php")){
	require __DIR__."/../../vendor/autoload.php";
}

assert_options(ASSERT_CALLBACK, function($file, $line, $code) {
	echo "Fail in {$file}:{$line};\n    `-> {$code}\n";
	#throw new Exception("Assert failed in {$file} on line {$line}; {$code}");
});

$testsToRun = [];

array_shift($argv);
$categories = $argv;

if(count($categories) == 0){
	$categories = array_map(function($path){
			$pathParts = explode("/", $path);
			return array_pop($pathParts);
		}, array_filter(glob(__DIR__."/*"), "is_dir")
	);
}

echo "Will test these categories:\n";
foreach($categories as $category){
	echo " - {$category}\n";
}

foreach($categories as $category){
	echo "\nTesting {$category}";
	echo "\n===================\n";
	$testsToRun = array_merge($testsToRun, glob(__DIR__."/${category}/*.php"));
}

foreach($testsToRun as $testFile){
	echo "($testFile)\n";
	include $testFile;
}

<?php

require "src/autoload.php";
if(file_exists("vendor/autoload.php")){
	include "vendor/autoload.php";
} else {
	echo "W: No vendor autoload file.\n";
}

$files = explode(
	"\n", 
	trim(`grep -RFl '\$fields' src/`)
);

$types = [
	integer::class => "INTEGER NOT NULL DEFAULT 0",
	string::class => "TEXT NOT NULL DEFAULT ''",
	float::class => "REAL NOT NULL DEFAULT 0.0",
];

foreach($files as $file):
	$className = preg_replace('`src/|\.php`', "", $file);
	$prefix = "  ";
?>

CREATE TABLE IF NOT EXISTS <?= $className::getTable() ?> (
<?php foreach($className::$fields as $field => $type): ?>
	<?= $prefix. $field ?> <?=
	$field == "id"
		?"INTEGER PRIMARY KEY"
		:$types[$type]
	?>

<?php $prefix = ", "; ?>
<?php endforeach; ?>
);
<?php endforeach; ?>

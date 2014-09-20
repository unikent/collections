<?php
/**
 * CLA system mock-up
 * 
 * @author Skylar Kelty <skylarkelty@gmail.com>
 */

global $CFG;

$CFG = new \stdClass();
$CFG->dirroot = dirname(__FILE__);

// Register the autoloader now.
spl_autoload_register(function($class) {
	global $CFG;

	$parts = explode('\\', $class);

	$filename = $CFG->dirroot . '/classes/' . implode('/', $parts) . '.php';
	if (file_exists($filename)) {
		require_once($filename);
	}
});

// Register the composer autoloaders.
require_once($CFG->dirroot . '/vendor/autoload.php');

// DB connection.
$DB = new \DML\MySQLi('mysql:host=localhost;port=3306;dbname=connect_development', 'root', '');

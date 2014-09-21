<?php
/**
 * Rapid Prototyping Framework in PHP.
 * 
 * @author Skylar Kelty <skylarkelty@gmail.com>
 */

global $CFG;

$CFG = new \stdClass();
$CFG->brand = 'Rapid';
$CFG->dirroot = dirname(__FILE__);
$CFG->cssroot = $CFG->dirroot . '/media/css';
$CFG->jsroot = $CFG->dirroot . '/media/js';
$CFG->wwwroot = 'http://localhost/';

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
$DB = new \Data\PDO('mysql', 'localhost', '3306', 'dbname', 'dbusername', 'dbpassword', 'table_prefix_');

// Cache connection.
$CACHE = new \Data\Memcached(array(
	array('localhost', '11211')
), 'prefix_');

// Start a session.
$SESSION = new \Auth\Session();

// Setup a guest user by default.
$USER = new \Auth\User();

// Output library.
$OUTPUT = new \Presentation\Output();

// Page library.
$PAGE = new \Presentation\Page();

// Set a default page title.
$PAGE->set_title('Rapid Protoyping Framework');

// Setup navigation.
$PAGE->menu(array(
	'Home' => '/',
	'Demo' => array(
		'Tables' => '/demo/table.php',
		'Forms' => '/demo/form.php',
		'Cache' => '/demo/cache.php'
	)
));

// Menu link for login or logout.
if (!$USER->loggedin()) {
	$PAGE->add_menu_item('Login', '/demo/login.php');
} else {
	$PAGE->add_menu_item('Logout', '/demo/logout.php');
}

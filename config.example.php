<?php
/**
 * Rapid Prototyping Framework in PHP.
 * 
 * @author Skylar Kelty <skylarkelty@gmail.com>
 */

global $CFG;

define("VERDI_INTERNAL", true);

$CFG = new \stdClass();
$CFG->brand = 'Rapid';
$CFG->dirroot = dirname(__FILE__);
$CFG->cssroot = $CFG->dirroot . '/media/css';
$CFG->jsroot = $CFG->dirroot . '/media/js';
$CFG->wwwroot = 'http://localhost/';
$CFG->tilesize = 256;
$CFG->cachedir = '/var/www/vhosts/kent.moodle/writable/';

require_once($CFG->dirroot . '/lib/corelib.php');

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
$DB = new \Rapid\Data\PDO('mysql', 'localhost', '3306', 'dbname', 'dbusername', 'dbpassword', 'table_prefix_');

// Cache connection.
$CACHE = new \Rapid\Data\Memcached(array(
	array('localhost', '11211')
), 'prefix_');

// Start a session.
//$SESSION = new \Rapid\Auth\Session();

// Setup a guest user by default.
//$USER = new \Rapid\Auth\User();

// Output library.
$OUTPUT = new \Rapid\Presentation\Output();

// Page library.
$PAGE = new \Rapid\Presentation\Page();

// Set a default page title.
$PAGE->set_title('Rapid Protoyping Framework');

// Setup navigation.
$PAGE->menu(array(
	'Home' => '/',
    'Zoomify' => '/demo/zoomify.php',
    'CALM' => '/demo/calm.php'
));

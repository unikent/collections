<?php
/**
 * Core Library.
 *
 * @package VERDI
 * @subpackage lib
 * @version 2.0
 * @author Skylar Kelty <S.Kelty@kent.ac.uk>
 * @copyright University of Kent
 */

defined("VERDI_INTERNAL") || die("This page cannot be accessed directly.");

if (!defined('CLI_SCRIPT')) {
    define('CLI_SCRIPT', false);
}

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
$DB = new \Rapid\Data\PDO(
    $CFG->database['adapter'],
    $CFG->database['host'],
    $CFG->database['port'],
    $CFG->database['database'],
    $CFG->database['username'],
    $CFG->database['password'],
    $CFG->database['prefix']
);

// Cache connection.
$CACHE = new \Rapid\Data\Memcached($CFG->cache['servers'], $CFG->cache['prefix']);

// Load config.
if (!defined('INSTALLING') || !INSTALLING) {
    $dbconfig = $CACHE->get('dbconfig');
    if (!$dbconfig) {
        try {
            $dbconfig = $DB->get_records('config');
        } catch (Exception $e) {
            die("Database tables are not present. Please run migrate.php");
        }
    }
}

if (!defined('CLI_SCRIPT') || !CLI_SCRIPT) {
    // Start a session.
    //$SESSION = new \Rapid\Auth\Session();

    // Setup a guest user by default.
    //$USER = new \Rapid\Auth\User();

    // Output library.
    $OUTPUT = new \Rapid\Presentation\Output();

    // Page library.
    $PAGE = new \Rapid\Presentation\Page();

    // Set a default page title.
    $PAGE->set_title('VERDI');

    // Setup navigation.
    $PAGE->menu($CFG->menu);
} else {
    if (isset($_SERVER['REMOTE_ADDR']) || php_sapi_name() != 'cli') {
        die("Must be run from CLI.");
    }
}

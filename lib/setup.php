<?php
/**
 * Core Library.
 *
 * @package SCAPI
 * @subpackage lib
 * @version 2.0
 * @author Skylar Kelty <S.Kelty@kent.ac.uk>
 * @copyright University of Kent
 */

defined("SCAPI_INTERNAL") || die("This page cannot be accessed directly.");

if (!defined('CLI_SCRIPT')) {
    define('CLI_SCRIPT', false);
}

if (CLI_SCRIPT) {
    if (isset($_SERVER['REMOTE_ADDR']) || php_sapi_name() != 'cli') {
        die("Must be run from CLI.");
    }
}

if (isset($CFG->_init_called)) {
    die("Setup script has already been called.");
}

$CFG->_init_called = microtime(true);

// Create the data dir if it doesnt exist.
if (!file_exists($CFG->datadir)) {
    if (mkdir($CFG->datadir, 0755, true)) {
        chown($CFG->datadir, 'w3collections');
        chgrp($CFG->datadir, 'pkg');
    } else {
        die("Cannot create data directory");
    }
}

// Check the data dir.
if (!is_writable($CFG->datadir)) {
    die("Datadir is not writable.");
}

require_once($CFG->dirroot . '/lib/corelib.php');

// Register the autoloader now.
spl_autoload_register(function($class) {
    global $CFG;

    $parts = explode('\\', $class);
    $first = array_shift($parts);
    if ($first !== "SCAPI") {
        return;
    }

    require_once($CFG->dirroot . '/classes/' . implode('/', $parts) . '.php');
});

// Register the composer autoloaders.
require_once($CFG->dirroot . '/vendor/autoload.php');

if (CLI_SCRIPT) {
    // Output library.
    $OUTPUT = new \Rapid\Presentation\CLI();
}

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
$dbconfig = $CACHE->get('dbconfig');
if (!$dbconfig) {
    try {
        $dbconfig = $DB->get_records('config');

        $CACHE->set('dbconfig', $dbconfig);
    } catch (Exception $e) {
        if (!defined('INSTALLING') || !INSTALLING) {
            die("Database tables are not present. Please run migrate.php");
        }
    }
}

if ($dbconfig) {
    foreach ($dbconfig as $record) {
        $name = $record->name;
        if (isset($CFG->$name)) {
            continue;
        }

        $CFG->$name = $record->value;
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
    $PAGE->require_css("//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css");
    $PAGE->require_js("//code.jquery.com/jquery-1.11.2.min.js");
    $PAGE->require_js("//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js");

    // Set a default page title.
    $PAGE->set_title($CFG->brand);

    // Setup navigation.
    $PAGE->menu(array(
        'Home' => '/index.php',
        'Zoomify' => '/demo/zoomify.php',
        'Formats' => '/demo/formats.php',
        'Calm' => '/demo/calm.php'
    ));

    // Developer mode?
    if (isset($CFG->developer_mode) && $CFG->developer_mode) {
        @error_reporting(E_ALL);
        set_error_handler(array('Rapid\\Core', 'error_handler'), E_ALL);
        set_exception_handler(array('Rapid\\Core', 'handle_exception'));
    }
}

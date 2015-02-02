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

// Create the cache dir if it doesnt exist.
if (!file_exists($CFG->cachedir)) {
    if (mkdir($CFG->cachedir, 0755, true)) {
        chown($CFG->cachedir, 'w3collections');
        chgrp($CFG->cachedir, 'pkg');
    } else {
        die("Cannot create cache directory");
    }
}

// Check the cache dir.
if (!is_writable($CFG->cachedir)) {
    die("Cachedir is not writable.");
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

// Init Rapid core.
\Rapid\Core::init();

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
}

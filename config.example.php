<?php
/**
 * Rapid Prototyping Framework in PHP.
 * 
 * @author Skylar Kelty <skylarkelty@gmail.com>
 */

global $CFG;

define("VERDI_INTERNAL", true);

$CFG = new \stdClass();
$CFG->brand = 'VERDI';
$CFG->dirroot = dirname(__FILE__);
$CFG->cssroot = $CFG->dirroot . '/media/css';
$CFG->jsroot = $CFG->dirroot . '/media/js';
$CFG->wwwroot = 'http://verdi-dev.kent.ac.uk:8080';
$CFG->tilesize = 256;
$CFG->cachedir = '/var/www/vhosts/verdi-dev.kent.ac.uk/writable/';

$CFG->database = array(
    'adapter' => 'mysql',
    'host' => 'localhost',
    'port' => '3306',
    'database' => 'verdi',
    'username' => 'root',
    'password' => '',
    'prefix' => 'v_'
);

$CFG->cache = array(
    'servers' => array(
        array('localhost', '11211')
    ),
    'prefix' => 'verdi_'
);

$CFG->menu = array(
    'Home' => '/',
    'Zoomify' => '/demo/zoomify.php',
    'Formats' => '/demo/formats.php',
    'CALM' => '/demo/calm.php'
);

require_once($CFG->dirroot . '/lib/setup.php');

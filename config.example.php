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
$CFG->cachedir = '/var/www/vhosts/verdi-dev.kent.ac.uk/writable';
$CFG->imageindir = '/var/www/vhosts/verdi-dev.kent.ac.uk/public/demo/images_in';

$CFG->developer_mode = true;

$CFG->calm_wsdl = 'http://vera.kent.ac.uk/XMLWrapper/ContentService.asmx?wsdl';
$CFG->calm_threads = 20;

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
    'Home' => '/demo/index.php',
    'Zoomify' => '/demo/zoomify.php',
    'Formats' => '/demo/formats.php',
    'Calm' => '/demo/calm.php'
);

require_once($CFG->dirroot . '/lib/setup.php');

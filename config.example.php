<?php
/**
 * Rapid Prototyping Framework in PHP.
 * 
 * @author Skylar Kelty <skylarkelty@gmail.com>
 */

global $CFG;

define("SCAPI_INTERNAL", true);

$CFG = new \stdClass();
$CFG->brand = 'SCAPI';
$CFG->dirroot = dirname(__FILE__);
$CFG->cssroot = $CFG->dirroot . '/media/css';
$CFG->jsroot = $CFG->dirroot . '/media/js';
$CFG->wwwroot = 'http://collections-dev.kent.ac.uk:8080';
$CFG->tilesize = 256;
$CFG->cachedir = '/var/www/vhosts/collections-dev.kent.ac.uk/writable';
$CFG->imageindir = '/var/www/vhosts/collections-dev.kent.ac.uk/public/demo/images_in';

$CFG->developer_mode = true;

$CFG->calm_wsdl = 'http://vera.kent.ac.uk/XMLWrapper/ContentService.asmx?wsdl';
$CFG->calm_threads = 20;

$CFG->database = array(
    'adapter' => 'mysql',
    'host' => 'localhost',
    'port' => '3306',
    'database' => 'collections_dev',
    'username' => 'root',
    'password' => '',
    'prefix' => 'c_'
);

$CFG->cache = array(
    'servers' => array(
        array('localhost', '11211')
    ),
    'prefix' => 'verdi_'
);

$CFG->solr = array(
    'endpoint' => array(
        'localhost' => array(
            'host' => '127.0.0.1',
            'port' => 8080,
            'path' => '/solr/cartoons/'
        )
    )
);

require_once($CFG->dirroot . '/lib/setup.php');

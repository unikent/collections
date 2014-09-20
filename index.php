<?php
/**
 * CLA system mock-up
 * 
 * @author Skylar Kelty <skylarkelty@gmail.com>
 */

require_once('config.php');

$obj = new \Data\Importers\BritishLibrary();
$obj->import('test.rdf');

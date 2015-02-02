<?php
/**
 * Rapid Prototyping Framework in PHP.
 * 
 * @author Skylar Kelty <skylarkelty@gmail.com>
 */

require_once(dirname(__FILE__) . '/../config.php');

$PAGE->set_url('/');
$PAGE->set_title("OpenLayers Request");

$id = required_param('id', PARAM_INT);
$request = required_param('request', PARAM_RAW_TRIMMED);

$image = new \SCAPI\Image\OpenLayers($id);

switch ($request) {
    case 'js':
        header("Content-Type: application/javascript");

        echo $image->get_js();
        die;
    break;
}
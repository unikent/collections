<?php
/**
 * Rapid Prototyping Framework in PHP.
 * 
 * @author Skylar Kelty <skylarkelty@gmail.com>
 */

require_once(dirname(__FILE__) . '/../config.php');

$PAGE->set_url('/api/images.php');
$PAGE->set_title("Image List");

$collection = required_param('collection', PARAM_INT);
$limit = optional_param('limit', 100, PARAM_INT);
$offset = optional_param('offset', 0, PARAM_INT);

$results = $DB->get_fieldset('files', 'id', array('type' => $collection), '', $limit, $offset);
echo json_encode($results);
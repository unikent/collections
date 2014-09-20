<?php
/**
 * CLA system mock-up
 * 
 * @author Skylar Kelty <skylarkelty@gmail.com>
 */

require_once('config.php');

$PAGE->set_url('/');
$PAGE->set_title("CLA Home");

echo $OUTPUT->header();
echo $OUTPUT->heading("CLA Administration");

$model = new \Models\Extract();
$model->id = 2;
$model->find();

print(var_dump($DB->get_model('Extract', array(
	'id' => 2,
	'start' => 1
))));

echo $OUTPUT->footer();

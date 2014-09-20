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
$model->extrapolate();

print(var_dump($model));

echo $OUTPUT->footer();

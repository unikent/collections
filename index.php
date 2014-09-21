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

$data = $DB->get_record('extract', array(
	'id' => 1
));

$form = new \Presentation\Form('/');
$form->import_model('Extract');
$form->set_data($data);
echo $form;

echo $OUTPUT->footer();

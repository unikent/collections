<?php
/**
 * Rapid Prototyping Framework in PHP.
 * 
 * @author Skylar Kelty <skylarkelty@gmail.com>
 */

require_once('config.php');

$PAGE->set_url('/');
$PAGE->set_title("Rapid Protoyping Framework Home");

echo $OUTPUT->header();
echo $OUTPUT->heading("Rapid Protoyping Framework Demo");

$data = $DB->get_record('extract', array(
	'id' => 1
));

$form = new \Presentation\Form('/');
$form->import_model('Extract');
$form->set_data($data);
echo $form;

echo $OUTPUT->footer();

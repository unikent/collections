<?php
/**
 * Rapid Prototyping Framework in PHP.
 * 
 * @author Skylar Kelty <skylarkelty@gmail.com>
 */

require_once('../config.php');

$PAGE->set_url('/demo/form.php');
$PAGE->set_title("Rapid Protoyping Framework Demo - Forms");

echo $OUTPUT->header();
echo $OUTPUT->heading("Forms");

$data = $DB->get_record('extract', array(
	'id' => 1
));

$form = new \Presentation\Form('/demo/form.php');
$form->import_model('Extract');
$form->set_data($data);
echo $form;

echo $OUTPUT->footer();

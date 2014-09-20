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

print(var_dump($DB->get_models('Extract')));

echo $OUTPUT->footer();

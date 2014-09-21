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

echo $OUTPUT->footer();

<?php
/**
 * Rapid Prototyping Framework in PHP.
 * 
 * @author Skylar Kelty <skylarkelty@gmail.com>
 */

require_once('../config.php');

$PAGE->set_url('/demo/login.php');
$PAGE->set_title("Rapid Protoyping Framework Demo - Login");

echo $OUTPUT->header();
echo $OUTPUT->heading("Login");

$auth = new \Auth\SimpleSAMLPHP();
$auth->login('/');

echo $OUTPUT->footer();

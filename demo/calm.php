<?php
/**
 * Index Page.
 *
 * @package VERDI
 * @subpackage Demo
 * @version 2.0
 * @author Skylar Kelty <S.Kelty@kent.ac.uk>
 * @copyright University of Kent
 */

require_once(dirname(__FILE__) . '/../config.php');

$PAGE->set_url('/demo/calm.php');
$PAGE->set_title("Calm import Demo");

//echo $OUTPUT->header();
//echo $OUTPUT->heading("Calm import Demo");

$obj = new \Calm\Catalog();
$obj->import();

//echo $OUTPUT->footer();

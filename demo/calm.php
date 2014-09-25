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

require_once('../config.php');

$PAGE->set_url('/demo/calm.php');
$PAGE->set_title("CALM import Demo");

//echo $OUTPUT->header();
//echo $OUTPUT->heading("CALM import Demo");

$obj = new \CALM\Accession();
$obj->import();

//echo $OUTPUT->footer();

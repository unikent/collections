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

$PAGE->set_url('/demo/index.php');
$PAGE->set_title("VERDI Demo");

echo $OUTPUT->header();
echo $OUTPUT->heading("VERDI Demo");

echo $OUTPUT->footer();

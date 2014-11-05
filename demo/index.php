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

echo <<<HTML
    <p>This is a demonstration of the new VERDI technology.</p>
    <p>Click one of the demo tabs above to view the different parts of VERDI.</p>
HTML;

echo $OUTPUT->footer();

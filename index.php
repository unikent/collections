<?php
/**
 * Index Page.
 *
 * @package Collections
 * @subpackage Demo
 * @version 2.0
 * @author Skylar Kelty <S.Kelty@kent.ac.uk>
 * @copyright University of Kent
 */

require_once(dirname(__FILE__) . '/config.php');

$PAGE->set_url('/index.php');
$PAGE->set_title("Special Collections");

echo $OUTPUT->header();
echo $OUTPUT->heading();

echo <<<HTML
    <p>This is a demonstration of the new Special Collections technology.</p>
    <p>Click one of the demo tabs above to view the different parts of Special Collections.</p>
HTML;

echo $OUTPUT->footer();

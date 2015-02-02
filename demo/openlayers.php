<?php
/**
 * Index Page.
 *
 * @package SCAPI
 * @subpackage Demo
 * @version 2.0
 * @author Skylar Kelty <S.Kelty@kent.ac.uk>
 * @copyright University of Kent
 */

require_once(dirname(__FILE__) . '/../config.php');

$PAGE->set_url('/demo/openlayers.php');
$PAGE->set_title("SCAPI - OpenLayers Demo");

$id = optional_param('id', 0, PARAM_INT);
if ($id > 0) {
    $PAGE->require_css('http://openlayers.org/en/v3.1.1/css/ol.css');
    $PAGE->require_js('http://openlayers.org/en/v3.1.1/build/ol.js');
    $PAGE->require_js('/api/openlayers.php?id=' . $id . '&request=js');
}

echo $OUTPUT->header();
echo $OUTPUT->heading('SCAPI - OpenLayers Demo');
echo '<p>Note: this is not working yet.</p>';

if ($id > 0) {
    echo "<ol class=\"breadcrumb\"><li><a href=\"/demo/openlayers.php\">OpenLayers</a></li><li>$id</li></ol>";
    echo '<div id="imagebox" class="imagebox"></div>';
} else {
    echo '<ul class="nav nav-pills nav-stacked" role="tablist">';
    $list = $DB->yield_records('files');
    foreach ($list as $image) {
        echo '<li><a href="?id=' . $image->id . '">' . $image->filename . '</a></li>';
    }
    echo '</ul>';
}

echo $OUTPUT->footer();

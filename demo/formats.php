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

$PAGE->set_url('/demo/formats.php');
$PAGE->set_title("Dynamic Resize Demo");

$id = optional_param('id', 0, PARAM_INT);

echo $OUTPUT->header();
echo $OUTPUT->heading();

if ($id > 0) {
    echo "<ol class=\"breadcrumb\"><li><a href=\"/demo/formats.php\">Formats</a></li><li>$id</li></ol>";
    echo "<h3>Thumbnail</h3><img src=\"../api/image.php?id={$id}&size=thumb\" alt=\"Thumb Size\"><br /><br />";
    echo "<h3>Standard</h3><img src=\"../api/image.php?id={$id}&size=standard\" alt=\"Standard Size\"><br /><br />";
    echo "<h3>Print</h3><img src=\"../api/image.php?id={$id}&size=print\" alt=\"Print Size\"><br /><br />";
    echo "<h3>Full</h3><img src=\"../api/image.php?id={$id}&size=full\" alt=\"Full Size\"><br /><br />";
} else {
    echo '<ul class="nav nav-pills nav-stacked" role="tablist">';
    $list = $DB->yield_records('files');
    foreach ($list as $image) {
        echo '<li><a href="?id=' . $image->id . '">' . $image->filename . '</a></li>';
    }
    echo '</ul>';
}

echo $OUTPUT->footer();

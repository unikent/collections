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

$PAGE->set_url('/demo/formats.php');
$PAGE->set_title("VERDI - Formats Demo");

echo $OUTPUT->header();
echo $OUTPUT->heading('VERDI - Dynamic Resize Demo');

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    echo "<h3>Thumbnail</h3><img src=\"/index.php?request={$id}/thumb\" alt=\"Thumb Size\"><br /><br />";
    echo "<h3>Standard</h3><img src=\"/index.php?request={$id}/standard\" alt=\"Standard Size\"><br /><br />";
    echo "<h3>Print</h3><img src=\"/index.php?request={$id}/print\" alt=\"Print Size\"><br /><br />";
    echo "<h3>Full</h3><img src=\"/index.php?request={$id}/full\" alt=\"Full Size\"><br /><br />";
} else {
    echo '<ul>';
    $list = $DB->get_records('file_map');
    foreach ($list as $image) {
        echo '<li><a href="?id=' . $image->id . '">' . $image->fullpath . '</a></li>';
    }
    echo '</ul>';
}

echo $OUTPUT->footer();

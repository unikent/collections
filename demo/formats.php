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

$PAGE->set_url('/demo/zoomify.php');
$PAGE->set_title("Zoomify Demo");

echo $OUTPUT->header();
echo $OUTPUT->heading('Dynamic Resize Demo');

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    echo "<h3>Thumbnail</h3><img src=\"/index.php?request={$id}/thumb\" alt=\"Thumb Size\"><br /><br />";
    echo "<h3>Standard</h3><img src=\"/index.php?request={$id}/standard\" alt=\"Standard Size\"><br /><br />";
    echo "<h3>Print</h3><img src=\"/index.php?request={$id}/print\" alt=\"Print Size\"><br /><br />";
    echo "<h3>Full</h3><img src=\"/index.php?request={$id}/full\" alt=\"Full Size\"><br /><br />";
} else {
    $list = glob('../media/images/*.jpg');
    foreach ($list as $image) {
        $pos = strrpos($image, '/') + 1;
        $pos2 = strrpos($image, '.');
        $id = substr($image, $pos, $pos2 - $pos);
        echo '<a href="?id=' . $id . '">Image ' . $id . '</a>';
    }
}

echo $OUTPUT->footer();

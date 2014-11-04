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
echo $OUTPUT->heading('Dynamic Zoomify Demo');

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    echo <<<HTML5
    <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="800" height="500" id="ZoomifyRotationViewer">
        <param name="flashvars" value="zoomifyImagePath=../index.php?request=$id">
        <param name="menu" value="false">
        <param name="src" value="../media/swf/ZoomifyRotationViewer.swf">
        <embed flashvars="zoomifyImagePath=../index.php?request=$id" src="../media/swf/ZoomifyRotationViewer.swf" menu="false" pluginspage="http://www.adobe.com/go/getflashplayer" type="application/x-shockwave-flash" width="800" height="500" name="ZoomifyRotationViewer"></embed>
    </object>
HTML5;
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

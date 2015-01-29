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

$PAGE->set_url('/demo/zoomify.php');
$PAGE->set_title("SCAPI - Zoomify Demo");

$id = optional_param('id', 0, PARAM_INT);

echo $OUTPUT->header();
echo $OUTPUT->heading('SCAPI - Dynamic Zoomify Demo');

if ($id > 0) {
    echo "<ol class=\"breadcrumb\"><li><a href=\"/demo/zoomify.php\">Zoomify</a></li><li>$id</li></ol>";

    echo <<<HTML5
    <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="800" height="500" id="ZoomifyRotationViewer">
        <param name="flashvars" value="zoomifyImagePath=../api/image.php?request=$id">
        <param name="menu" value="false">
        <param name="src" value="../media/swf/ZoomifyRotationViewer.swf">
        <embed flashvars="zoomifyImagePath=../api/image.php?request=$id" src="../media/swf/ZoomifyRotationViewer.swf" menu="false" pluginspage="http://www.adobe.com/go/getflashplayer" type="application/x-shockwave-flash" width="800" height="500" name="ZoomifyRotationViewer"></embed>
    </object>
HTML5;
} else {
    echo '<ul class="nav nav-pills nav-stacked" role="tablist">';
    $list = $DB->yield_records('files');
    foreach ($list as $image) {
        echo '<li><a href="?id=' . $image->id . '">' . $image->filename . '</a></li>';
    }
    echo '</ul>';
}

echo $OUTPUT->footer();

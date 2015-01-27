<?php
/**
 * Catalog API.
 *
 * @package VERDI
 * @subpackage api\bcad
 * @version 2.0
 * @author Skylar Kelty <S.Kelty@kent.ac.uk>
 * @copyright University of Kent
 */

require_once(dirname(__FILE__) . '/../../config.php');

$limit = optional_param('limit', 0, PARAM_INT);
$offset = optional_param('offset', 0, PARAM_INT);

$params = array();
foreach ($_GET as $k => $v) {
    $params[$k] = $v;
}

header("Content-Type: application/json\n");

$files = array();
foreach ($DB->yield_records('bcad_files') as $file) {
    if (!isset($files[$file->recordid])) {
        $files[$file->recordid] = array();
    }

    $files[$file->recordid][] = $file->id;
}


echo '[';

// Returns all catalog entries by default.
$delim = '';
foreach ($DB->yield_records('calm_catalog', $params, '*', '', $limit, $offset) as $row) {

    // Attach files.
    if (!empty($files[$row->id])) {
        $row->files = $files[$row->id];
    }

    echo $delim . json_encode($row);
    $delim = ',';
}

echo ']';

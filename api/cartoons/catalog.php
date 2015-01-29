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

$limit = optional_param('_limit', 0, PARAM_INT);
$offset = optional_param('_offset', 0, PARAM_INT);
$fields = optional_param('_fields', '*', PARAM_RAW);

if ($fields != '*') {
    $fields = explode(',', $fields);

    $validfields = \Verdi\Models\Catalog::get_field_list();
    $fields = array_filter($fields, function($v) use($validfields) {
        return in_array($v, $validfields);
    });

    $fields = implode(',', $fields);
}

$params = array();
foreach ($_GET as $k => $v) {
    if (strpos($k, '_') !== 0) {
        $params[$k] = $v;
    }
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
foreach ($DB->yield_records('calm_catalog', $params, $fields, '', $limit, $offset) as $row) {

    // Attach files.
    if (!empty($files[$row->id])) {
        $row->files = $files[$row->id];
    }

    echo $delim . json_encode($row);
    $delim = ',';
}

echo ']';

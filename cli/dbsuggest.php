<?php
/**
 * VERDI CLI scripts.
 *
 * @package VERDI
 * @subpackage lib
 * @version 2.0
 * @author Skylar Kelty <S.Kelty@kent.ac.uk>
 * @copyright University of Kent
 */

define('CLI_SCRIPT', true);

require_once(dirname(__FILE__) . '/../config.php');

// Runs through a table and suggests new max sizes for columns.
$table = $argv[1];

$columns = array();
$columntypes = array();

$records = $DB->get_records($table);

foreach ($records as $record) {
    if (empty($columns)) {
        $columns = array();
        foreach ((array)$record as $k => $v) {
            $columns[$k] = array();
            $columntypes[$k] = '';
        }
    }

    foreach ((array)$record as $k => $v) {
        $len = strlen($v);
        $columns[$k][] = $len;

        if (!empty($v)) {
            if (empty($columntypes[$k])) {
                if (is_numeric($v)) {
                    $columntypes[$k] = 'int';
                }
            } else {
                if ($columntypes[$k] == 'int' && !is_numeric($v)) {
                    $columntypes[$k] = 'varchar';
                }
            }
        }

        if ($len > $columns[$k]) {
            $columns[$k][] = $len;
        }
    }
}

foreach ($columns as $k => $v) {
    $max = max($v);
    $avg = array_sum($v) / count($v);
    $type = $columntypes[$k] == '' ? 'varchar' : $columntypes[$k];

    echo $k . ": " . $max  . " / " . round($avg) . " / $type\n";
}

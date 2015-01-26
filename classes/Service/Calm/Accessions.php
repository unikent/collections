<?php
/**
 * Core Library.
 *
 * @package VERDI
 * @subpackage lib
 * @version 2.0
 * @author Skylar Kelty <S.Kelty@kent.ac.uk>
 * @copyright University of Kent
 */

namespace Verdi\Service\Calm;

defined("VERDI_INTERNAL") || die("This page cannot be accessed directly.");

class Accessions extends Importer
{
    /**
     * Returns search types.
     */
    protected function get_search() {
        return array(
            'dbname' => 'Accessn',
            'elementSet' => 'DC',
            'expr' => '(RecordID=*)'
        );
    }

    /**
     * Returns search types.
     */
    protected function get_search_on_hit($id) {
        return array(
            'dbname' => 'Accessn',
            'HitLstPos' => $id
        );
    }

    /**
     * Processes a hit, returns an array of data for the object.
     */
    protected function get_record($xml) {
        // Check the code exists.
        $code = (string)$xml->Summary->AccNo;
        if (empty($code)) {
            return false;
        }

        $validfields = \Verdi\Models\Accession::get_field_list();

        $result = array(
            'id' => $code
        );

        foreach ($xml->Summary->children() as $k => $v) {
            if ($k == 'AccNo') {
                continue;
            }

            // Set = DataSet
            if ($k == 'Set') {
                $k = 'DataSet';
            }

            if (!in_array($k, $validfields)) {
                continue;
            }

            $v = trim((string)$v);
            if (!empty($v)) {
                $result[$k] = $v;
            }
        }

        return $result;
    }

    /**
     * Imports everything.
     */
    protected function process($record) {
        global $DB;

        $DB->update_or_insert('calm_accession', array('id' => $record['id']), $record);
    }
}

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

namespace Calm;

defined("VERDI_INTERNAL") || die("This page cannot be accessed directly.");

class Accession extends Importer
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
    protected function hit($xml) {
        // Check the code exists.
        $code = (string)$xml->Summary->AccNo;
        if (empty($code)) {
            return false;
        }

        $result = array(
            'accno' => $code,
            'values' => array()
        );

        foreach ($xml->Summary->children() as $k => $v) {
            if ($k == 'AccNo') {
                continue;
            }

            $v = trim((string)$v);
            if (!empty($v)) {
                $result['values'][$k] = $v;
            }
        }

        return $result;
    }

    /**
     * Imports everything.
     */
    public function import() {
        global $DB;

        $gen = $this->get_all();
        foreach ($gen as $hit) {
            $accno = $hit['accno'];

            foreach ($hit['values'] as $k => $v) {
                $current = $DB->get_record('accession', array(
                    'accno' => $accno,
                    'name' => $k
                ));

                // New ones.
                if (!$current) {
                    $DB->insert_record('accession', array(
                        'accno' => $accno,
                        'name' => $k,
                        'value' => $v
                    ));

                    continue;
                }

                // Updates.
                if (!$current->value != $v) {
                    $DB->update_record('accession', array(
                        'id' => $current->id,
                        'value' => $v
                    ));
                }
            }

            // Grab accession model for current accno.
            $accession = \Models\Accession::get($accno);

            // Deletes.
            foreach ($accession->values() as $v) {
                if (!isset($hit['values'][$v->name])) {
                    $DB->delete_records('accession', array(
                        'accno' => $accno,
                        'name' => $v->name
                    ));
                }
            }
        }
    }
}

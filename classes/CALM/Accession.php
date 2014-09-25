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

namespace CALM;

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
}

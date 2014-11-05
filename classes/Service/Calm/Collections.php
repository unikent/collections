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

namespace Service\Calm;

defined("VERDI_INTERNAL") || die("This page cannot be accessed directly.");

class Collections extends Importer
{
    /**
     * Returns search types.
     */
    protected function get_search() {
        return array(
            'dbname' => 'Catalog',
            'elementSet' => 'DC',
            'expr' => '(Repository="British Cartoon Archive")AND((Level="Collection")OR(Level="Section")OR(Level="SubSection")OR(Level="SubSubSection")OR(Level="Series")OR(Level="SubSeries")OR(Level="SubSubSeries"))'
        );
    }

    /**
     * Returns search types.
     */
    protected function get_search_on_hit($id) {
        return array(
            'dbname' => 'Catalog',
            'HitLstPos' => $id
        );
    }

    /**
     * Processes a hit, returns an array of data for the object.
     */
    protected function get_record($xml) {
        $validfields = \Models\Collection::get_field_list();

        $result = array();
        foreach ($xml->Summary->children() as $k => $v) {
            $k = trim((string)$k);
            $v = trim((string)$v);

            if (in_array($k, $validfields)) {
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

        $compare = array(
            'RefNo' => $record['RefNo'],
            'AltRefNo' => $record['AltRefNo']
        );

        $DB->update_or_insert('calm_collections', $compare, $record);
    }
}

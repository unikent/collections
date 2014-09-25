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

class Collection extends Importer
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
    protected function hit($xml) {
        $fields = \Models\Collection::get_field_list();

        $result = array();

        foreach ($xml->Summary->children() as $k => $v) {
            $v = trim((string)$v);
            if (!empty($v)) {
                $result[$k] = $v;
            }
        }

        return $result;
    }
}

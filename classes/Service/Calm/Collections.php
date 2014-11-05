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
        $map = array(
            'RefNo' => 'code',
            'Title' => 'title',
            'Date' => 'date',
            'Description' => 'description',
            'Level' => 'level_t',
            'Extent' => 'extent_t'
        );

        $result = array();
        foreach ($map as $k => $v) {
            $result[$v] = '';
        }

        foreach ($xml->Summary->children() as $k => $v) {
            $k = trim((string)$k);
            $v = trim((string)$v);

            if (!empty($v) && isset($map[$k])) {
                $result[$map[$k]] = $v;
            }
        }

        return $result;
    }

    /**
     * Imports everything.
     */
    protected function process($record) {
        global $DB;

        // TODO - redo deletes.

        //static $keys = array();

        //$keys[] = $record['code'];

        $collection = $DB->get_record('calm_collections', $record);

        // New ones.
        if (!$collection) {
            $DB->insert_record('calm_collections', $record);
            return;
        }

        // Updates.
        $record['id'] = $current->id;
        $DB->update_record('calm_collections', $record);

        // Deletes.
        /*$livekeys = $DB->get_fieldset('calm_collections', 'code');
        foreach ($livekeys as $key) {
            if (!in_array($key, $keys)) {
                $DB->delete_records('calm_collections', array(
                    'code' => $key
                ));
            }
        }*/
    }
}
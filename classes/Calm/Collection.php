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
    public function import() {
        global $DB;

        $keys = array();

        $gen = $this->get_all();
        foreach ($gen as $hit) {
            $keys[] = $hit['code'];

            $collection = $DB->get_record('collections', $hit);

            // New ones.
            if (!$collection) {
                $DB->insert_record('collections', $hit);

                continue;
            }

            // Updates.
            if (
                $collection->code != $hit['code'] ||
                $collection->title != $hit['title'] ||
                $collection->date != $hit['date'] ||
                $collection->description != $hit['description'] ||
                $collection->level_t != $hit['level_t'] ||
                $collection->extent_t != $hit['extent_t']
            ) {
                $hit['id'] = $current->id;
                $DB->update_record('collections', $hit);
            }
        }

        // Deletes.
        $livekeys = $DB->get_fieldset('collections', 'code');
        foreach ($livekeys as $key) {
            if (!in_array($key, $keys)) {
                $DB->delete_records('collections', array(
                    'code' => $key
                ));
            }
        }
    }
}

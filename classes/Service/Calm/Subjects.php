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

class Subjects extends Importer
{
    /**
     * Returns search types.
     */
    protected function get_search() {
        return array(
            'dbname' => 'Subjects',
            'elementSet' => 'DC',
            'expr' => '(RecordID=*)'
        );
    }

    /**
     * Returns search types.
     */
    protected function get_search_on_hit($id) {
        return array(
            'dbname' => 'Subjects',
            'HitLstPos' => $id
        );
    }

    /**
     * Processes a hit, returns an array of data for the object.
     */
    protected function get_record($xml) {
        $term = (string)$xml->Summary->Term;
        if (empty($term)) {
            return false;
        }

        $values = array();
        if (isset($xml->Summary->BT)) {
            $values[] = (string)$xml->Summary->BT;
        }

        if (isset($xml->Summary->USE)) {
            $values[] = (string)$xml->Summary->USE;
        }

        if (isset($xml->Summary->UF)) {
            $values[] = (string)$xml->Summary->UF;
        }

        return array(
            'term' => $term,
            'related' => $values,
        );
    }

    /**
     * Imports everything.
     */
    protected function process($record) {
        static $relatedids = array();

        $related = array();

        foreach ($record['related'] as $item) {
            if (!isset($relatedids[$item])) {
                $relatedids[$item] = $this->import_record($item);
            }

            $related[] = $relatedids[$item];
        }

        $id = $this->import_record($record['term']);
        $relatedids[$record['term']] = $id;
        $related = array_unique($related);

        foreach ($related as $id2) {
            // Map the related.
            $this->import_related($id, $id2);
        }
    }

    /**
     * Imports a specific record into the database.
     */
    private function import_record($term) {
        global $DB;

        $rec = $DB->get_record('calm_subjects', array('name' => $term));
        if (!$rec) {
            return $DB->insert_record('calm_subjects', array('name' => $term));
        }

        return $rec->id;
    }

    /**
     * Relates item 1 to item 2.
     */
    private function import_related($id, $id2) {
        global $DB;

        $params = array(
            'subj' => $id,
            'related' => $id2
        );

        $rec = $DB->get_record('calm_subjects_related', $params);
        if (!$rec) {
            $DB->insert_record('calm_subjects_related', $params);
        }
    }
}

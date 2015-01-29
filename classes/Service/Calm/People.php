<?php
/**
 * Core Library.
 *
 * @package SCAPI
 * @subpackage lib
 * @version 2.0
 * @author Skylar Kelty <S.Kelty@kent.ac.uk>
 * @copyright University of Kent
 */

namespace Verdi\Service\Calm;

defined("SCAPI_INTERNAL") || die("This page cannot be accessed directly.");

class People extends Importer
{
    /**
     * Returns search types.
     */
    protected function get_search() {
        return array(
            'dbname' => 'Persons',
            'elementSet' => 'DC',
            'expr' => '(RecordID=*)'
        );
    }

    /**
     * Returns search types.
     */
    protected function get_search_on_hit($id) {
        return array(
            'dbname' => 'Persons',
            'HitLstPos' => $id
        );
    }

    /**
     * Processes a hit, returns an array of data for the object.
     */
    protected function get_record($xml) {
        $code = (string)$xml->Summary->Code;
        if (empty($code)) {
            return false;
        }

        $personname = (string)$xml->Summary->PersonName;
        if (empty($personname)) {
            return false;
        }

        $fullname = $personname;
        foreach ($xml->Summary->NonPreferredTerm as $term) {
            if (!empty($term)) {
                $fullname .= " [$term]";
            }
        }

        $nationality = (string)$xml->Summary->Nationality;

        return array(
            'code' => $code,
            'personname' => $personname,
            'fullname' => $fullname,
            'nationality' => $nationality
        );
    }

    /**
     * Imports everything.
     */
    protected function process($record) {
        global $DB;

        $person = $DB->get_record('calm_people', $record);
        if (!$person) {
            $DB->insert_record('calm_people', $record);
        }
    }
}

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

namespace Models;

defined("VERDI_INTERNAL") || die("This page cannot be accessed directly.");

class Catalog extends Accession
{


    /**
     * List of valid fields
     */
    public static function get_field_list() {
        return array(
            'alsoPublishedIn',
            'alsoPublishedOn',
            'artist',
            'copyrightContactDetails',
            'copyrightHolder',
            'date',
            'dayOfYear',
            'description',
            'displayCopyright',
            'displayRecord',
            'embeddedText',
            'endDate',
            'format',
            'genre',
            'impliedText',
            'isSingleDate',
            'level',
            'location',
            'locationOfArtwork',
            'medium',
            'notes',
            'personCode',
            'publisher',
            'relatedPersonCode',
            'relatedRecord',
            'relatestocartoon',
            'restrictImageDisplay',
            'series',
            'series_s',
            'size',
            'startDate',
            'subject',
            'technique',
            'title',
            'webtab'
        );
    }

    /**
     * Find all catalogs.
     */
    public static function get_all() {
        global $DB;

        $values = (array)$DB->get_records('calm_catalog');

        if (empty($values)) {
            return null;
        }

        $records = array();
        foreach ($values as $record) {
            $key = $record->refno . '_' . $record->altrefno;
            if (!isset($records[$key])) {
                $records[$key] = array(
                    'refno' => $record->refno,
                    'altrefno' => $record->altrefno,
                );
            }

            $records[$key][$record->name] = $record->value;
        }

        $objects = array();
        foreach ($records as $k => $v) {
            $obj = new static();
            foreach ($v as $name => $value) {
                $obj->$name = $value;
            }

            $objects[$k] = $obj;
        }

        return $objects;
    }

    /**
     * Find catalog.
     */
    public static function get($refno, $altrefno) {
        global $DB;

        $values = (array)$DB->get_records('calm_catalog', array(
            'refno' => $refno,
            'altrefno' => $altrefno
        ));

        if (empty($values)) {
            return null;
        }

        $obj = new static();
        foreach ($values as $record) {
            $k = $record->name;
            $v = $record->value;
            $obj->$k = $v;
        }

        return $obj;
    }
}

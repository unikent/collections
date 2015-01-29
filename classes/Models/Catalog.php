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

namespace SCAPI\Models;

defined("SCAPI_INTERNAL") || die("This page cannot be accessed directly.");

class Catalog extends \Rapid\Data\DBModel
{
    /**
     * List of valid fields
     */
    public static function get_field_list() {
        return array(
            'id',
            'refno',
            'altrefno',
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
}

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

namespace Verdi\Models;

defined("SCAPI_INTERNAL") || die("This page cannot be accessed directly.");

class Accession extends \Rapid\Data\DBModel
{
    /**
     * List of valid fields
     */
    public static function get_field_list() {
        return array(
            'id',
            'AccessionCategory',
            'AccessStatus',
            'AcqTerms',
            'AdminHistory',
            'Copies',
            'Copyright',
            'Created',
            'Creator',
            'CustodialHistory',
            'Date',
            'DepositorId',
            'Description',
            'Extent',
            'Location',
            'Modified',
            'Modifier',
            'PublnNote',
            'RecordID',
            'RecordType',
            'Repository',
            'DataSet',
            'Title'
        );
    }
}

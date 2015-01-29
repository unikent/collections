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

class Collection extends \Rapid\Data\DBModel
{
    public static function get_field_list() {
        return array(
            'id',
            'RecordType',
            'IDENTITY',
            'Level',
            'Repository',
            'RefNo',
            'AltRefNo',
            'AccNo',
            'Extent',
            'Artist',
            'Title',
            'Date',
            'UserText7',
            'UserWrapped7',
            'UserWrapped8',
            'Publisher',
            'UserWrapped6',
            'PubDate',
            'CONTENT',
            'Description',
            'UserText4',
            'Format',
            'Technique',
            'UserText6',
            'UserText1',
            'UserWrapped2',
            'UserWrapped3',
            'Series',
            'Thumbnail',
            'Notes',
            'UserWrapped5',
            'Appraisal',
            'Arrangement',
            'Ignition',
            'UserText5',
            'UserWrapped9',
            'UserText3',
            'UserText8',
            'UserText9',
            'ACCESS',
            'Location',
            'ClosedUntil',
            'AccessConditions',
            'Copyright',
            'Suspension',
            'ChassisNo',
            'AccessStatus',
            'PhysicalDescription',
            'CONSERVATIONREQUIRED',
            'ConservationPriority',
            'ALLIED_MATERIALS',
            'Originals',
            'Copies',
            'RelatedMaterial',
            'RelatedRecord',
            'URL',
            'CATALOGUE_STATUS',
            'CatalogueStatus',
            'CountryCode',
            'RepositoryCode',
            'EHFDPublisher',
            'EHPDLanguage',
            'ADMIN_DETAILS',
            'RecordID',
            'Creator',
            'Created',
            'Modifier',
            'Modified',
            'UserWrapped4',
            'RCN'
        );
    }
}

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

class Accession
{
    private $data;

    /**
     * Constructor.
     */
    public function __construct() {
        $this->data = array();
    }

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

    /**
     * Find accession by number
     */
    public static function get($id) {
        global $DB;

        $values = (array)$DB->get_records('calm_accession', array(
            'id' => $id
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

    /**
     * Magic set.
     */
    public function __set($name, $value) {
        $this->data[$name] = $value;
    }

    /**
     * Magic get.
     */
    public function __get($name) {
        return $this->data[$name];
    }

    /**
     * Magic isset.
     */
    public function __isset($name) {
        return isset($this->data[$name]);
    }

    /**
     * Magic unset.
     */
    public function __unset($name) {
        unset($this->data[$name]);
    }

    /**
     * Returns all data.
     */
    public function values() {
        return $this->data;
    }
}

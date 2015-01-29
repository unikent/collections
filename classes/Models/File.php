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

class File extends \Rapid\Data\DBModel
{
    const TYPE_CARTOONS = 1;
    const TYPE_COLLECTIONS = 1;

    /**
     * Constructor.
     */
    public function __construct() {
        parent::__construct('files');
    }

    /**
     * List of valid fields
     */
    public static function get_field_list() {
        return array(
            'id',
            'type',
            'recordid',
            'filename'
        );
    }

    /**
     * Returns the full path to a file based on an ID.
     */
    public static function get_path($id) {
        global $CFG, $DB;

        $path = $DB->get_field('files', 'filename', array(
            'id' => $id
        ));

        if (!$path) {
            return null;
        }

        return $CFG->imageindir . '/' . $path;
    }

    /**
     * Get the extension of a file.
     */
    public static function get_extension($filename) {
        $ext = substr($filename, strrpos($filename, '.') + 1);
        return strtolower($ext);
    }

    /**
     * Returns all cartoons files.
     */
    public static function yield_cartoons() {
        global $DB;

        return $DB->yield_records('files', array(
            'type' => static::TYPE_CARTOONS
        ));
    }

    /**
     * Returns all collections files.
     */
    public static function yield_collections() {
        global $DB;

        return $DB->yield_records('files', array(
            'type' => static::TYPE_COLLECTIONS
        ));
    }
}

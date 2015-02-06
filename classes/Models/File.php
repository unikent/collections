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
    const TYPE_COLLECTIONS = 2;

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

        return $CFG->datadir . '/files/' . $path;
    }

    /**
     * Returns a filename.
     */
    public static function get_filename($path, $removeextension = false) {
        $filename = substr($path, strrpos($path, '/') + 1);

        if ($removeextension) {
            do {
                $filename = substr($filename, 0, strrpos($filename, '.'));
            } while (strrpos($filename, '.') !== false);
        }

        return $filename;
    }

    /**
     * Import a file.
     */
    public static function import_file($path) {
        global $CFG;

        $hash = md5($path);
        $section = substr($hash, 0, 2);
        $block = substr($hash, 2, 2);

        $filename = static::get_filename($path);
        $newpath = "{$CFG->datadir}/files/{$section}/{$block}";
        ensure_path_exists($newpath);

        $newfilename = "{$newpath}/{$filename}";
        copy($path, $newfilename);

        return "{$section}/{$block}/" . $filename;
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

<?php
/**
 * Image Processing Library.
 *
 * @package SCAPI
 * @subpackage lib
 * @version 2.0
 * @author Skylar Kelty <S.Kelty@kent.ac.uk>
 * @copyright University of Kent
 */

namespace SCAPI\Cron\Task\BCAD;

defined("SCAPI_INTERNAL") || die("This page cannot be accessed directly.");

class FileMap extends \SCAPI\Cron\Task
{
    public function do_run() {
        global $CFG, $DB;

        // Grab a list of files.
        $list = $this->find_files($CFG->imageindir);
        
        // Delete any old entries from DB.
        $DB->execute("
            DELETE f.*
            FROM {files} f
            LEFT OUTER JOIN {calm_catalog} c
                ON c.recordid=f.id
            WHERE c.id IS NULL AND f.type = :type
        ", array(
            'type' => \SCAPI\Models\File::TYPE_CARTOONS
        ));

        $validfiles = array();

        // Add new ones.
        foreach ($list as $entry) {
            // Find related record.
            $recordid = substr($entry, strrpos($entry, '/') + 1);
            do {
                $recordid = substr($recordid, 0, strrpos($recordid, '.'));
            } while (strrpos($recordid, '.') !== false);

            // Find record.
            $record = $DB->get_record('calm_catalog', array(
                'refno' => $recordid
            ));

            if ($record) {
                $entry = substr($entry, 1);
                $filerecord = array(
                    'type' => \SCAPI\Models\File::TYPE_CARTOONS,
                    'recordid' => $record->id,
                    'filename' => $entry
                );
                if ($DB->count_records('files', $filerecord) <= 0) {
                    echo "Adding '$entry' to index...\n";
                    $DB->insert_record('files', $filerecord);
                }

                $validfiles[$entry] = $record->id;
            }
        }

        // Delete any entries from DB with no matching file.
        $entries = \SCAPI\Models\File::yield_cartoons();
        foreach ($entries as $entry) {
            if (!isset($validfiles[$entry->filename])) {
                echo "Removing '$entry->filename' from index...\n";
                $DB->delete_records('files', $entry);
            }
        }
    }

    private function find_files($dir) {
        global $CFG;

        $files = array();

        $entries = glob($dir . "/*", GLOB_ONLYDIR);
        foreach ($entries as $entry) {
            $files = array_merge($files, $this->find_files($entry));
        }

        $extensions = array('jpg', 'jpeg', 'tiff', 'tif', 'gif', 'png');

        $entries = glob($dir . "/*.*");
        foreach ($entries as $entry) {
            $ext = \SCAPI\Models\File::get_extension($entry);
            if (!in_array($ext, $extensions)) {
                continue;
            }

            $files[] = substr($entry, strlen($CFG->imageindir));
        }

        return $files;
    }
}
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

namespace SCAPI\Cron\Task\Files;

defined("SCAPI_INTERNAL") || die("This page cannot be accessed directly.");

class Cartoons extends \SCAPI\Cron\Task
{
    /**
     * Run.
     */
    public function do_run() {
        global $CFG, $DB;

        // Grab a list of files.
        $list = $this->find_files($CFG->imagein['cartoons']);
        
        // Delete any old entries from DB.
        $DB->execute("
            DELETE f.*
            FROM {files} f
            LEFT OUTER JOIN {calm_catalog} c
                ON c.id=f.recordid
            WHERE c.id IS NULL AND f.type = :type
        ", array(
            'type' => \SCAPI\Models\File::TYPE_CARTOONS
        ));

        $validfiles = array();

        // Add new ones.
        foreach ($list as $entry) {
            // Find related record.
            $record = $DB->get_record('calm_catalog', array(
                'refno' => \SCAPI\Models\File::get_filename($entry, true)
            ));

            if ($record) {
                $entry = \SCAPI\Models\File::import_file($entry);

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

    /**
     * Find all files within a directory.
     */
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

            $files[] = $entry;
        }

        return $files;
    }
}
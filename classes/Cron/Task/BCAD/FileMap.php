<?php
/**
 * Image Processing Library.
 *
 * @package VERDI
 * @subpackage lib
 * @version 2.0
 * @author Skylar Kelty <S.Kelty@kent.ac.uk>
 * @copyright University of Kent
 */

namespace Verdi\Cron\Task\BCAD;

defined("VERDI_INTERNAL") || die("This page cannot be accessed directly.");

class FileMap extends \Verdi\Cron\Task
{
    public function do_run() {
        global $CFG, $DB;

        // Grab a list of files.
        $list = $this->find_files($CFG->imageindir);
        
        // Delete any old entries from DB.
        $DB->execute("
            DELETE f.*
            FROM {bcad_files} f
            LEFT OUTER JOIN {calm_catalog} c
                ON c.recordid=f.id
            WHERE c.id IS NULL
        ");

        $validfiles = array();

        // Add new ones.
        foreach ($list as $entry) {
            // Find related record.
            $recordid = substr($entry, strrpos($entry, '/'));
            do {
                $recordid = substr($recordid, 0, strrpos($recordid, '.'));
            } while (strrpos($recordid, '.') !== false);

            // Find record.
            $record = $DB->get_record('calm_catalog', array(
                'refno' => $recordid
            ));

            if ($record) {
                echo "Adding '$entry' to index...\n";
                $DB->insert_record('bcad_files', array(
                    'recordid' => $record->id,
                    'filename' => $entry
                ));

                $validfiles[$entry] = $record->id;
            }
        }

        // Delete any entries from DB with no matching file.
        $entries = $DB->get_records('bcad_files');
        foreach ($entries as $entry) {
            if (!isset($validfiles[$entry->filename])) {
                echo "Removing '$entry->filename' from index...\n";
                $DB->delete_records('bcad_files', $entry);
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
            $ext = get_file_extension($entry);
            if (!in_array($ext, $extensions)) {
                continue;
            }

            $files[] = substr($entry, strlen($CFG->imageindir) + 1);
        }

        return $files;
    }
}
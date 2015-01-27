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
        $entries = $DB->get_records('file_map');
        foreach ($entries as $entry) {
            $k = array_search($entry->fullpath, $list);
            if ($k === false) {
                echo "Removing '$entry->fullpath' from index...\n";
                $DB->delete_records('file_map', $entry);
            } else {
                unset($list[$k]);
            }
        }
        unset($entries);

        // Add new ones.
        foreach ($list as $entry) {
            echo "Adding '$entry' to index...\n";
            $DB->insert_record('file_map', array(
                'fullpath' => $entry
            ));
        }
    }

    private function find_files($dir) {
        global $CFG;

        $files = array();

        $entries = glob($dir . "/*", GLOB_ONLYDIR);
        foreach ($entries as $entry) {
            $files = array_merge($files, $this->find_files($entry));
        }

        $extensions = array('jpg', 'jpeg', 'tiff', 'gif', 'png');

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
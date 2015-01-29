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

namespace Verdi\Cron\Task\SOLR;

defined("VERDI_INTERNAL") || die("This page cannot be accessed directly.");

class Cartoons extends \Verdi\Cron\Task
{
    public function do_run() {
        global $CFG, $DB;

        $client = new \Solarium\Client($CFG->solr);

        $this->run_deletions($client);

        $count = $DB->count_records('calm_catalog');
        $batchsize = 10000;
        for ($i = 0; $i < $count; $i += $batchsize) {
            $this->run_update_batch($client, $i, $batchsize);
        }

        $this->run_optimize($client);
    }

    /**
     * Do deletions.
     */
    private function run_deletions($client) {
        global $DB;

        // Get a list of valid IDS.
        $ids = $DB->get_fieldset('calm_catalog', 'id');

        // Get a list of IDs in SOLR.
        $query = $client->createSelect();
        $query->setQuery('*:*');
        $query->setFields(array('id'));
        $solrids = $client->select($query);

        // Work out what needs to be deleted.
        $update = $client->createUpdate();
        foreach ($solrids as $document) {
            if (!in_array($document->id, $ids)) {
                $update->addDeleteById($document->id);
            }
        }

        // Send the updates to SOLR.
        $update->addCommit();
        $client->update($update);
    }

    /**
     * Batch up.
     */
    private function run_update_batch($client, $start, $count) {
        global $DB;

        static $suffixes = array(
            'id' => '',
            'dayOfYear' => '_i',
            'endDate' => '_i',
            'isSingleDate' => '_i',
            'startDate' => '_i'
        );

        $update = $client->createUpdate();

        foreach ($DB->yield_records('calm_catalog', array(), '*', '', $count, $start) as $row) {
            $doc = $update->createDocument();
            foreach ((array)$row as $k => $v) {
                if (isset($suffixes[$k])) {
                    $k = $k . $suffixes[$k];
                } else {
                    $k = $k . '_t';
                }

                if (!empty($v)) {
                    $doc->$k = $v;
                }
            }

            $update->addDocument($doc);
        }

        $update->addCommit();

        return $client->update($update);
    }

    /**
     * Optimize the SOLR indexes.
     */
    private function run_optimize($client) {
        // Optimize after a big import.
        $update = $client->createUpdate();
        $update->addOptimize(true, false, 5);
        $client->update($update);
    }
}
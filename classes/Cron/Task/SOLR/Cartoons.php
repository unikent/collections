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

        $count = $DB->count_records('calm_catalog');
        $batchsize = 10000;
        for ($i = 0; $i < $count; $i += $batchsize) {
            $this->run_batch($client, $i, $batchsize);
        }

        // Optimize after a big import.
        $update = $client->createUpdate();
        $update->addOptimize(true, false, 5);
        $client->update($update);
    }

    /**
     * Batch up.
     */
    private function run_batch($client, $start, $count) {
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
}
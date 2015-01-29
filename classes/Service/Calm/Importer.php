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

namespace Verdi\Service\Calm;

defined("SCAPI_INTERNAL") || die("This page cannot be accessed directly.");

abstract class Importer extends \Verdi\Service\Service
{
    /** SOAP Ref */
    private $_soap;
    /**
     * Constructor.
     */
    public function __construct() {
        global $CFG;

        $this->_soap = new \SoapClient($CFG->calm_wsdl, array(
            'trace' => true,
            'exceptions' => true,
            'connection_timeout' => 600
        ));
    }

    /**
     * Returns all records of this type.
     */
    protected function get_count() {
        $search = $this->get_search();
        $result = $this->_soap->Search($search);
        return $result->SearchResult;
    }

    /**
     * Processes a hit, returns an array of data for the object.
     */
    protected function get_record($xml) {
        $result = array();

        foreach ($xml->Summary->children() as $k => $v) {
            $k = trim((string)$k);
            $v = trim((string)$v);

            if (!empty($v)) {
                $result[$k] = $v;
            }
        }

        return $result;
    }

    /**
     * Imports everything.
     */
    public function import() {
        global $CFG;

        $count = $this->get_count();
        echo "Found $count records.\n";

        $perthread = floor($count / $CFG->calm_threads);
        for ($i = 0; $i < $CFG->calm_threads; $i++) {
            $start = $i * $perthread;
            $end = $start + $perthread;
            if ($i == ($CFG->calm_threads - 1)) {
                $end = $count;
            }

            echo "Spawning thread number {$i} to process {$start} - {$end} records.\n";

            $this->run(array(
                $start, $end
            ));
        }
    }

    /**
     * Returns all records of this type.
     */
    protected function perform($data) {
        global $CFG;

        $cachedir = $CFG->cachedir . "/" . get_class($this);
        if (!file_exists($cachedir)) {
            mkdir($cachedir);
        }

        list($start, $end) = $data;

        for ($i = $start; $i <= $end; $i++) {
            $filename = "{$cachedir}/{$i}.xml";

            $xml = '';
            if (!file_exists($filename) || !$CFG->developer_mode) {
                $search = $this->get_search_on_hit($i);
                $result = $this->_soap->SummaryHeader($search);

                // Store the xml file in a backup folder.
                file_put_contents($filename, $result->SummaryHeaderResult);

                // Load the XML.
                $xml = simplexml_load_string($result->SummaryHeaderResult);
            } else {
                // Load the XML from cache.
                $xml = simplexml_load_string(file_get_contents($filename));
            }


            $record = $this->get_record($xml);
            if ($record) {
                $this->process($record);
            }
        }
    }

    /**
     * Returns search types.
     */
    protected abstract function get_search();

    /**
     * Returns search types.
     */
    protected abstract function get_search_on_hit($id);

    /**
     * Imports a record.
     */
    protected abstract function process($record);
}

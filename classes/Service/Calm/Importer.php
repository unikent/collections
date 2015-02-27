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

namespace SCAPI\Service\Calm;

defined("SCAPI_INTERNAL") || die("This page cannot be accessed directly.");

abstract class Importer extends \SCAPI\Service\Service
{
    /**
     * Constructor.
     */
    public function __construct() {
    }

    /**
     * Get a SOAP connection.
     */
    public function get_soap() {
        global $CFG;

        return new \SoapClient($CFG->calm_wsdl, array(
            'trace' => true,
            'exceptions' => true,
            'connection_timeout' => 600
        ));
    }

    /**
     * Returns all records of this type.
     */
    protected function get_count($soap) {
        $search = $this->get_search();
        $result = $soap->Search($search);
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

        $soap = $this->get_soap();
        $count = $this->get_count($soap);
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
    protected final function perform($data) {
        global $CFG;

        $cachedir = $CFG->cachedir . "/" . get_class($this);
        if (!file_exists($cachedir)) {
            mkdir($cachedir);
        }

        list($start, $end) = $data;

        $soap = $this->get_soap();
        $this->get_count($soap);
        for ($i = $start; $i < $end; $i++) {

            $search = $this->get_search_on_hit($i);
            $result = '';
            try {
                $result = $soap->SummaryHeader($search);
            } catch (\Exception $e) {
                echo $e->getMessage() . "\n";
                $soap = $this->get_soap();
                $this->get_count($soap);
                continue;
            }

            if (empty($result) || empty($result->SummaryHeaderResult)) {
                continue;
            }

            $xml = preg_replace('/(<\?xml[^?]+?)utf-16/i', '$1utf-8', $result->SummaryHeaderResult);

            // Load the XML.
            $xml = simplexml_load_string($xml);

            // Grab the record.
            if ($xml) {
                $record = $this->get_record($xml);
                if ($record) {
                    $this->process($record);
                }
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

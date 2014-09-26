<?php
/**
 * Core Library.
 *
 * @package VERDI
 * @subpackage lib
 * @version 2.0
 * @author Skylar Kelty <S.Kelty@kent.ac.uk>
 * @copyright University of Kent
 */

namespace CALM;

defined("VERDI_INTERNAL") || die("This page cannot be accessed directly.");

abstract class Importer
{
    const WSDL = 'http://vera.kent.ac.uk/XMLWrapper/ContentService.asmx?wsdl';

    /**
     * Returns all records of this type.
     */
    public function get_all() {
        $soap = new \SoapClient(self::WSDL, array(
            'trace' => true,
            'exceptions' => true,
            'connection_timeout' => 600
        ));

        $search = $this->get_search();

        $result = $soap->Search($search);
        $count = $result->SearchResult;

        for ($i = 0; $i < $count; $i++) {
            $nsearch = $this->get_search_on_hit($i);
            $result = $soap->SummaryHeader($nsearch);

            $xml = simplexml_load_string($result->SummaryHeaderResult);

            $hit = $this->hit($xml);
            if ($hit) {
                yield $hit;
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
     * Processes a hit, returns an array of data for the object.
     */
    protected function hit($xml) {
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
    protected abstract function import();
}

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

namespace Models;

defined("VERDI_INTERNAL") || die("This page cannot be accessed directly.");

class Catalog extends Accession
{
    /**
     * Find accession by number
     */
    public static function get($refno, $altrefno) {
        global $DB;

        $values = (array)$DB->get_records('calm_catalog', array(
            'refno' => $refno,
            'altrefno' => $altrefno
        ));

        if (empty($values)) {
            return null;
        }

        $obj = new static();
        foreach ($values as $k => $v) {
            $obj->$k = $v;
        }

        return $obj;
    }
}

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

class Catalog extends Importer
{
    /**
     * Returns search types.
     */
    protected function get_search() {
        return array(
            'dbname' => 'Catalog',
            'elementSet' => 'DC',
            'expr' => '(Repository="British Cartoon Archive")AND((Level="Item")OR(Level="Piece"))'
        );
    }

    /**
     * Returns search types.
     */
    protected function get_search_on_hit($id) {
        return array(
            'dbname' => 'Catalog',
            'HitLstPos' => $id
        );
    }

    /**
     * Processes a hit, returns an array of data for the object.
     */
    protected function hit($xml) {
        // Not sure what this is.
        if (strtolower((string)$xml->Summary->UserText5) != 'yes') {
            return false;
        }

        // Check for invalid AltRefNo.
        $code = trim((string)$xml->Summary->AltRefNo);
        if (!empty($code) && preg_match('/ |\//', $code)) {
            return false;
        }

        // Check for present RefNo/AltRefNo.
        $refno = trim((string)$xml->Summary->RefNo);
        if (empty($code) && empty($refno)) {
            return false;
        }

        // Find the webtab.
        $webtab = strtolower(trim((string)$xml->UserWrapped9));
        if (empty($webtab)) {
            $webtab = 'cartoon';
        }

        // Work out a relationship.
        $relatestocartoon = trim((string)$xml->UserText1);
        if ($webtab == 'cartoon' && empty($relatestocartoon)) {
            return false;
        }

        // Sanity checks.
        if ($webtab == 'cartoon' && empty($code)) {
            return false;
        }

        if ($webtab != 'cartoon' && !empty($relatestocartoon)) {
            $relatestocartoon = '';
        }

        // Date stuff.
        $date = (string)$xml->Date;
        echo $date;
    }
}

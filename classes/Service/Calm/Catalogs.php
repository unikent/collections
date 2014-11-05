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

namespace Service\Calm;

defined("VERDI_INTERNAL") || die("This page cannot be accessed directly.");

class Catalogs extends Importer
{
    private $date_parser;

    /**
     * Constructor.
     */
    public function __construct() {
        parent::__construct();

        $this->date_parser = new \Util\DateParser();
    }

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
    protected function get_record($xml) {
        // Should we even display this?
        $displayRecord = trim(strtolower((string)$xml->Summary->UserText5));
        if ($displayRecord != 'yes') {
            return false;
        }

        // Check for present RefNo/AltRefNo.
        $refno = trim((string)$xml->Summary->RefNo);
        if (empty($code) && empty($refno)) {
            return false;
        }

        // Check for invalid AltRefNo.
        $code = trim((string)$xml->Summary->AltRefNo);
        if (!empty($code) && preg_match('/ |\//', $code)) {
            return false;
        }

        // Find the webtab.
        $webtab = strtolower(trim((string)$xml->Summary->UserWrapped9));
        if (empty($webtab)) {
            $webtab = 'cartoon';
        }

        // Work out a relationship.
        $relatestocartoon = trim((string)$xml->Summary->UserText1);
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
        $date = (string)$xml->Summary->Date;
        list($start_date, $end_date, $is_single_date) = $this->date_parser->parse($date);
        if (!$start_date) {
            if (!empty($date) && !$is_single_date) {
                echo 'Couldnt recognize date from #' . $refno . ', (' . $date . ') will import anyway but wont be searchable on date ranges.';
            }

            $start_date = '';
            $end_date = '';
            $is_single_date = '';
            $day_of_year = '';
        } else {
            if ($is_single_date) {
                $end_date = $start_date;
            }

            $day_of_year = $start_date->get('MMdd');
            $start_date = $start_date->get('yyyyMMdd');
            $end_date = $end_date->get('yyyyMMdd');
            $is_single_date = $is_single_date ? 1 : -1;
        }

        return array(
            'refno' => $refno,
            'code' => $code,
            'values' => array(
                'webtab' => $webtab,
                'relatestocartoon' => $relatestocartoon,
                'webtab' => $webtab,
                'date' => $date,
                'startDate' => $start_date,
                'endDate' => $end_date,
                'isSingleDate' => $is_single_date,
                'dayOfYear' => $day_of_year,
                'subject' => $xml->Summary->Subject,
                'relatedRecord' => $xml->Summary->RelatedRecord,
                'title' => $xml->Summary->Title,
                'artist' => $xml->Summary->Artist,
                'publisher' => $xml->Summary->Publisher,
                'level' => $xml->Summary->Level,
                'size' => $xml->Summary->UserText6,
                'technique' => $xml->Summary->Technique,
                'alsoPublishedIn' => $xml->Summary->UserWrapped6,
                'alsoPublishedOn' => $xml->Summary->PubDate,
                'relatedPersonCode' => $xml->Summary->UserText3,
                'format' => $xml->Summary->Format,
                'description' => $xml->Summary->Description,
                'impliedText' => $xml->Summary->UserWrapped3,
                'embeddedText' => $xml->Summary->UserWrapped2,
                'notes' => $xml->Summary->Notes,
                'series' => $xml->Summary->Series,
                'series_s' => $xml->Summary->Series,
                'copyrightHolder' => $xml->Summary->Copyright,
                'displayCopyright' => $xml->Summary->ChassisNo,
                'copyrightContactDetails' => $xml->Summary->Suspension,
                'medium' => $xml->Summary->UserWrapped1,
                'genre' => $xml->Summary->UserText4,
                'locationOfArtwork' => $xml->Summary->Originals,
                'location' => $xml->Summary->Location,
                'personCode' => $xml->Summary->PersonCode,
                'displayRecord' => $displayRecord,
                'restrictImageDisplay' => $xml->Summary->Ignition
            )
        );
    }

    /**
     * Imports everything.
     */
    protected function process($record) {
        global $DB;

        $refno = $record['refno'];
        $altrefno = $record['code'];

        foreach ($record['values'] as $k => $v) {
            $current = $DB->get_record('calm_catalog', array(
                'refno' => $refno,
                'altrefno' => $altrefno,
                'name' => $k
            ));

            // New ones.
            if (!$current) {
                $DB->insert_record('calm_catalog', array(
                    'refno' => $refno,
                    'altrefno' => $altrefno,
                    'name' => $k,
                    'value' => $v
                ));

                continue;
            } else {
                // Updates.
                if (!$current->value != $v) {
                    $DB->update_record('calm_catalog', array(
                        'id' => $current->id,
                        'value' => $v
                    ));
                }
            }
        }

        // Grab catalog model for current refno/altrefno.
        $catalog = \Models\Catalog::get($refno, $altrefno);

        // Deletes.
        foreach ($catalog->values() as $v) {
            if (!isset($record['values'][$v->name])) {
                $DB->delete_records('calm_catalog', array(
                    'refno' => $refno,
                    'altrefno' => $altrefno,
                    'name' => $v->name
                ));
            }
        }
    }
}

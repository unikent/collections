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

namespace Verdi\Util;

defined("VERDI_INTERNAL") || die("This page cannot be accessed directly.");

/**
 * This was back-ported from bcad-import.
 */
class DateParser
{
    public function parse($d) {
        if (empty($d)) {
            return array(false, null, null);
        }

        $d = preg_replace('/\[|\]/', '', $d);

        $d = preg_replace('/-/', '- ', $d); // otherwise Jan-Mar doesnt work.. zend_date is pretty fussy about case.

        $d = ucwords(strtolower($d));

        $d = preg_replace('/ +/', ' ', $d);
        $d = preg_replace('/ ?- ?/', '-', $d);
        $d = trim($d);

        if (in_array($d, array('Unknown', 'N.d', 'Undated'))) {
            return array(false, null, true);
        }

        if (preg_match('/^C((irc)?a?)[\s\.]*?(?<s>\d{4})(.+?(?<e>\d{4}))?/', $d, $m)) {
            $end = null;
            if (isset($m['e'])) {
                $end = new \Zend_Date($m['e'], 'yyyy');
                $end->add(10, \Zend_Date::YEAR);
            } else {
                $end = new \Zend_Date($m['s'], 'yyyy');
                $end->add(10, \Zend_Date::YEAR);
            }
            $start = new \Zend_Date($m['s'], 'yyyy');
            $start->sub(10, \Zend_Date::YEAR);

            $d = $start->get('dd/MM/yyyy') . '-' . $end->get('dd/MM/yyyy');
        }

        $s = explode('-', $d);
        if (count($s) > 1) {
            $r = array();
            list($start, $end, $single) = $this->parsed($s[1]);

            if (!$start) {
                return array(false, null, null);
            }

            $r[2] = false;

            if (preg_match('/^\d{1,2}$/', $s[0])) {
                $r[1] = $start;
                $start = clone($start);
                $start->set($s[0], \Zend_Date::DAY);
                $r[0] = $start;
            } elseif (preg_match('/^[a-zA-Z]+$/', $s[0])) {
                $r[1] = $end;
                $start = $start->get('yyyy-MM-dd');
                $start = new \Zend_Date($start, 'yyyy-MM-dd');
                $start->setMonth($s[0]); // and setting the month sets the year why?
                $start->setYear($r[1]->getYear());
                $r[0] = $start;
            } elseif (preg_match('/^(?<d>\d{1,2}) (?<m>[a-zA-Z]+)$/', $s[0], $m)) {
                list($r[0], $_, $_) = $this->parsed($s[0] . ' ' . $start->get('yyyy'));
                $r[1] = $start;
            } else {
                list($r[0], $_, $_) = $this->parsed($s[0]);
                $r[1] = $end ? $end : $start;
            }

            return $r;
        }

        return $this->parsed($d);
    }

    public function parsed($d) {

        $r = false;

        $pats = array();
        $pats [] = 'dd/MM/yyyy';
        $pats [] = 'd/M/yyyy';
        $pats [] = 'dd/M/yyyy';
        $pats [] = 'd/MM/yyyy';

        $pats [] = 'd MMMM yyyy';
        $pats [] = 'dd MMMM yyyy';
        $pats [] = 'd MMM yyyy';
        $pats [] = 'dd MMM yyyy';

        foreach ($pats as $p) {
            if (!$r) {
                $r = $this->_parse($d, $p);
            }
        }

        $patd = array();
        $patd [] = array('MMMM yyyy', \Zend_Date::MONTH);
        $patd [] = array('MMM yyyy', \Zend_Date::MONTH);
        $patd [] = array('yyyy', \Zend_Date::YEAR);

        foreach ($patd as $p) {
            if (!$r) {
                $r = $this->_parsed($d, $p[0], $p[1]);
            }
        }

        if (!$r and preg_match('/(?<y>\d{4})s$/', $d, $m)) {
            $r = $this->_s($m['y']);
        }

        if (!$r) {
            $r = array(false, null, true);
        }

        return $r;
    }

    private function _parse($d, $p) {
        $r = false;
        try {
            $zd = new \Zend_Date($d, $p);
            if ($zd->get($p) == $d) {
                $r = array($zd, false, true);
            }
        } catch (\Exception $e) {
        } // its not valid
        return $r;
    }

    private function _parsed($d, $p, $inc, $num = 1) {
        $r = false;
        try {
            $zd = new \Zend_Date($d, $p);
            if ($zd->get($p) == $d) {
                $r = array(clone($zd), false, false);
                $zd->add($num, $inc);
                $zd->sub($num, \Zend_Date::DAY);
                $r[1] = $zd;
            }
        } catch (\Exception $e) {
        } // again with the not valid
        return $r;
    }

    private function _s($d) {
        $r = false;
        if ($d % 100) {
            if (!($d % 10)) {
                $r = array();
                $r[0] = new \Zend_Date($d . '-01-01', 'yyyy-MM-dd');
                $r[1] = new \Zend_Date($d + 9 . '-12-31', 'yyyy-MM-dd');
                $r[2] = false;
            } else {
                // error in here to be reported somehow ?
            }
        } else {
            $r = array();
            $r[0] = new \Zend_Date($d . '-01-01', 'yyyy-MM-dd');
            $r[1] = new \Zend_Date($d + 99 . '-12-31', 'yyyy-MM-dd');
            $r[2] = false;
        }
        return $r;
    }
}

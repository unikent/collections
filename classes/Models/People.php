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

namespace Verdi\Models;

defined("VERDI_INTERNAL") || die("This page cannot be accessed directly.");

class People extends \Rapid\Data\DBModel
{
    /**
     * List of valid fields
     */
    public static function get_field_list() {
        return array(
            'code',
            'personname',
            'fullname',
            'nationality'
        );
    }
}

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

namespace Service;

defined("VERDI_INTERNAL") || die("This page cannot be accessed directly.");

class PreProcessor extends Service
{
    public function perform($data) {
        $image = new \Image\Processor($data['id']);
        $image->preprocess();
    }
}

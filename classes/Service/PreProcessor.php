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

namespace Verdi\Service;

defined("SCAPI_INTERNAL") || die("This page cannot be accessed directly.");

class PreProcessor extends Service
{
    protected function perform($data) {
        $image = new \Verdi\Image\Processor($data['id']);
        $image->preprocess();
    }
}

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

namespace SCAPI\Service;

defined("SCAPI_INTERNAL") || die("This page cannot be accessed directly.");

class PreProcessor extends Service
{
    protected function perform($data) {
        $image = new \SCAPI\Image\Processor($data['id']);
        $image->preprocess();
    }
}

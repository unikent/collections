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

class ZoomifyGen extends Service
{
    protected function perform($data) {
        $image = new \SCAPI\Image\Zoomify($data['id']);
        $image->preprocess();
    }
}

<?php
/**
 * Image Processing Library.
 *
 * @package SCAPI
 * @subpackage lib
 * @version 2.0
 * @author Skylar Kelty <S.Kelty@kent.ac.uk>
 * @copyright University of Kent
 */

namespace SCAPI\Cron\Task\Calm;

defined("SCAPI_INTERNAL") || die("This page cannot be accessed directly.");

class Accessions extends \SCAPI\Cron\Task
{
    public function do_run() {
        $obj = new \SCAPI\Service\Calm\Accessions();
        $obj->import();
    }
}
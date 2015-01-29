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

namespace Verdi\Cron\Task\Calm;

defined("SCAPI_INTERNAL") || die("This page cannot be accessed directly.");

class Catalogs extends \Verdi\Cron\Task
{
    public function do_run() {
        $obj = new \Verdi\Service\Calm\Catalogs();
        $obj->import();
    }
}
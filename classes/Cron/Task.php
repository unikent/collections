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

namespace Verdi\Cron;

defined("SCAPI_INTERNAL") || die("This page cannot be accessed directly.");

abstract class Task
{
    public static function run() {
        $obj = new static();
        $obj->do_run();
    }

    public abstract function do_run();
}
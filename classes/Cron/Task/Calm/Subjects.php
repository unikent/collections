<?php
/**
 * Image Processing Library.
 *
 * @package VERDI
 * @subpackage lib
 * @version 2.0
 * @author Skylar Kelty <S.Kelty@kent.ac.uk>
 * @copyright University of Kent
 */

namespace Verdi\Cron\Task\Calm;

defined("VERDI_INTERNAL") || die("This page cannot be accessed directly.");

class Subjects extends \Verdi\Cron\Task
{
    public function do_run() {
        $obj = new \Verdi\Service\Calm\Subjects();
        $obj->import();
    }
}
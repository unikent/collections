<?php
/**
 * VERDI CLI scripts.
 *
 * @package VERDI
 * @subpackage lib
 * @version 2.0
 * @author Skylar Kelty <S.Kelty@kent.ac.uk>
 * @copyright University of Kent
 */

define('CLI_SCRIPT', true);
define('INSTALLING', true);

require_once(dirname(__FILE__) . '/../config.php');

\Cron\Task\Catalog::run();

<?php
/**
 * SCAPI CLI scripts.
 *
 * @package SCAPI
 * @subpackage lib
 * @version 2.0
 * @author Skylar Kelty <S.Kelty@kent.ac.uk>
 * @copyright University of Kent
 */

define('CLI_SCRIPT', true);
define('INSTALLING', true);

require_once(dirname(__FILE__) . '/../config.php');

$migrate = new \Verdi\Data\MySQL\Migrate();
$migrate->run();

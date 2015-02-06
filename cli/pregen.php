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

require_once(dirname(__FILE__) . '/../config.php');

$id = $argv[1];

$image = new \SCAPI\Image\Zoomify($id);
$image->preprocess();
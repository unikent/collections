<?php
/**
 * CLA system mock-up
 * 
 * @author Skylar Kelty <skylarkelty@gmail.com>
 */

require_once('config.php');

$obj = new \Data\Query\BritishLibrary();
//$obj->search_isbn('9780729408745');
//$obj->search_issn('0955-6664');
//$obj->search_author('Voltaire');
$obj->search_title('Journal of nutritional ');

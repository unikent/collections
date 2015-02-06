<?php
/**
 * Rapid Prototyping Framework in PHP.
 * 
 * @author Skylar Kelty <skylarkelty@gmail.com>
 */

require_once(dirname(__FILE__) . '/../config.php');

$PAGE->set_url('/api/image.php');
$PAGE->set_title("Image Request");

$id = required_param('id', PARAM_INT);
$format = required_param('format', PARAM_ALPHA);

$image = new \SCAPI\Image\Processor($id);

// This outputs a few different things.
switch ($format) {
    case "thumb":
        $image->output_as(170, 0, 0, 170, 50);
    break;

    case "print":
        $image->output_as(3400, 0, 0, 3400, 60);
    break;

    case "full":
        $image->output_as(3400, 0, 0, 3400, 40);
    break;

    case "standard":
    default:
        $image->output_as(482, 0, 0, 482, 60);
    break;
}

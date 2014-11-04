<?php
/**
 * Rapid Prototyping Framework in PHP.
 * 
 * @author Skylar Kelty <skylarkelty@gmail.com>
 */

require_once('config.php');

$PAGE->set_url('/');
$PAGE->set_title("Rapid Protoyping Framework Home");

// We require a valid request.
$request = required_param('request', PARAM_RAW_TRIMMED);

// The request is a mixture of an ID and a file request.
list($id, $request) = explode('/', $request, 2);

// This outputs a few different things.
switch ($request) {
    case "ImageProperties.xml":
        header("Content-type: text/xml; charset=utf-8");
        $image = new \Image\Processor($id);
        echo $image->get_xml();

        // Spawn a service to pre-process the other images.
        $preprocessor = new \Service\PreProcessor(array(
            "id" => $id,
            "filename" => $id
        ));
        $preprocessor->run();
    break;

    default:
        $image = new \Image\Processor($id);

        $parts = explode('/', $request);
        switch ($parts[0]) {
            case 'TileGroup0':
                $tile = substr($parts[1], 0, strpos($parts[1], '.'));
                $image->output_tile($id, $tile);
            break;

            case "print":
                $image->output_as(3400, 0, 0, 3400, 60);
            break;

            case "full":
                $image->output_as(3400, 0, 0, 3400, 40);
            break;

            case "standard":
                $image->output_as(482, 0, 0, 482, 60);
            break;

            case "thumb":
                $image->output_as(170, 0, 0, 170, 50);
            break;
        }
    break;
}
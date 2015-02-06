<?php
/**
 * Rapid Prototyping Framework in PHP.
 * 
 * @author Skylar Kelty <skylarkelty@gmail.com>
 */

require_once(dirname(__FILE__) . '/../config.php');

$PAGE->set_url('/');
$PAGE->set_title("Zoomify Request");

// We require a valid request.
$request = required_param('request', PARAM_RAW_TRIMMED);

// The request is a mixture of an ID and a file request.
list($id, $request) = explode('/', $request, 2);

// This outputs a few different things.
switch ($request) {
    case "ImageProperties.xml":
        header("Content-type: text/xml; charset=utf-8");

        $data = $CACHE->get("zoomify-xml-$id");
        if (!$data) {
            $image = new \SCAPI\Image\Zoomify($id);
            $data = $image->get_xml();
            $CACHE->set("zoomify-xml-$id", $data);
        }

        echo $data;

        // Spawn a service to pre-process the other images.
        //$preprocessor = new \SCAPI\Service\ZoomifyGen();
        //$preprocessor->run(array(
        //    "id" => $id,
        //    'test' => 'test'
        //));
    break;

    default:                   
        $parts = explode('/', $request);
        $tile = substr($parts[1], 0, strpos($parts[1], '.'));

        $image = new \SCAPI\Image\Zoomify($id);
        $image->output_tile($tile);
    break;
}

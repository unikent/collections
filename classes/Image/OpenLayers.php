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

namespace SCAPI\Image;

defined("SCAPI_INTERNAL") || die("This page cannot be accessed directly.");

class OpenLayers extends Processor
{
    /**
     * Output required JS for Openlayers.
     */
    public function get_js() {
        $width = $this->get_width();
        $height = $this->get_height();
        $zoomifyurl = new \Rapid\URL('/api/zoomify.php');

        $out = <<<HTML5
            var imagemap = new ol.Map({
                target: 'imagebox',
                layers: [
                    new ol.layer.Tile({
                        source: new ol.source.Zoomify({
                            url: '$zoomifyurl',
                            size: [$width, $height],
                        })
                    })
                ],
                view: new ol.View({
                    center: [0, 0],
                    zoom: 0
                })
            });
HTML5;

        return $out;
    }
}

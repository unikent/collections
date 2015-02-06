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
    private $scaleorig;
    private $scaleinfo;

    public function __construct($id) {
        global $CFG;

        parent::__construct($id);

        $this->set_scale_info();

        $imageid = $this->get_image_id();
        if (!file_exists("{$CFG->cachedir}/tiles/{$imageid}")) {
            mkdir("{$CFG->cachedir}/tiles/{$imageid}", 0755, true);
        }
    }

    /**
     * Sets the scale info for the image.
     */
    private function set_scale_info() {
        $width = $this->get_width();
        $height = $this->get_height();

        $this->scaleorig = array($width, $height);
        $this->scaleinfo[] = array($width, $height);

        $tilesize = $this->get_tile_size();

        while (($width > $tilesize) || ($height > $tilesize)) {
            $width = floor($width / 2.0);
            $height = floor($height / 2.0);

            $this->scaleinfo[] = array($width, $height);
        }

        $this->scaleinfo = array_reverse($this->scaleinfo);
    }

    /**
     * Get the maximum depth of the image.
     */
    protected function get_max_depth() {
        return count($this->scaleinfo);
    }

    /**
     * How many tiles should we split this into?
     */
    protected function get_num_tiles($zoom = 0) {
        list($width, $height) = $this->scaleinfo[$zoom];
        $tilesize = $this->get_tile_size();

        $y_tiles = ceil($height / $tilesize);
        $x_tiles = ceil($width / $tilesize);

        return array($x_tiles, $y_tiles);
    }

    /**
     * Output required JS for Openlayers.
     */
    public function get_js($id) {
        $width = $this->get_width();
        $height = $this->get_height();
        $tileurl = new \Rapid\URL('/api/openlayers.php');
        $tileurl .= '?id=' . $id . '&request={z}-{x}-{y}';

        $out = <<<HTML5
            var extent = [0, 0, $width, $height];
            var projection = new ol.proj.Projection({
                code: 'collections-image',
                units: 'pixels',
                extent: extent
            });
            var imagemap = new ol.Map({
                target: 'imagebox',
                layers: [
                    new ol.layer.Tile({
                        transitionEffect: 'resize',
                        source: new ol.source.XYZ({
                            url: '$tileurl',
                            minZoom: 0,
                            maxZoom: 6,

                        })
                    })
                ],
                view: new ol.View({
                    center: ol.extent.getCenter(extent),
                    zoom: 0
                })
            });
HTML5;

        return $out;
    }

    /**
     * Set the tile we want.
     */
    public function output_tile($tile) {
        global $CFG;
    echo $tile;
        header("Content-type: image/jpeg");

        // Does this file already exist in cache?
        $imageid = $this->get_image_id();
        $cache = $CFG->cachedir . "/tiles/{$imageid}/{$tile}.jpg";
        if (file_exists($cache)) {
            header('X-Sendfile: ' . $cache);
            die;
        }

        // We want to grab a sepcific tile.
        list($zoom, $x, $y) = explode('-', $tile);

        // Special case for the thumbnail.
        if ($zoom == 0) {
            $image = $this->get_thumbnail();
        } else {
            $image = $this->crop_tile($zoom, $x, $y);
        }

        $this->save($image, $cache);
        header('X-Sendfile: ' . $cache);
    }

    /**
     * Crop the image to a particular tile.
     */
    protected function crop_tile($zoom, $x, $y) {
        list($width, $height) = $this->scaleinfo[$zoom];
        $image = $this->resize($width, $height);
        $tilesize = $this->get_tile_size();

        $x = $x * $tilesize;
        $y = $y * $tilesize;

        $endx = min($width, $x + $tilesize);
        $endy = min($height, $y + $tilesize);

        $image->cropImage($endx - $x, $endy - $y, $x, $y);
        return $image;
    }

    protected function get_tile_size()
    {
        return 256;
    }
}

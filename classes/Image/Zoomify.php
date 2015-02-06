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

class Zoomify extends Processor
{
    private $scaleorig;
    private $scaleinfo;

    public function __construct($id) {
        global $CFG;

        parent::__construct($id);

        $imageid = $this->get_image_id();
        ensure_path_exists("{$CFG->cachedir}/tiles/{$imageid}");
    }

    /**
     * Returns scale info.
     */
    private function get_scale_info() {
        if (isset($this->scaleinfo)) {
            return $this->scaleinfo;
        }

        $this->scaleinfo = array();

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

        return $this->scaleinfo;
    }

    /**
     * Get the maximum depth of the image.
     */
    protected function get_max_depth() {
        $scaleinfo = $this->get_scale_info();
        return count($scaleinfo);
    }

    /**
     * How many tiles should we split this into?
     */
    protected function get_num_tiles($zoom = 0) {
        $scaleinfo = $this->get_scale_info();
        list($width, $height) = $scaleinfo[$zoom];
        $tilesize = $this->get_tile_size();

        $y_tiles = ceil($height / $tilesize);
        $x_tiles = ceil($width / $tilesize);

        return array($x_tiles, $y_tiles);
    }

    /**
     * Crop the image to a particular tile.
     */
    protected function crop_tile($image, $zoom, $x, $y) {
        $scaleinfo = $this->get_scale_info();
        list($width, $height) = $scaleinfo[$zoom];
        $tilesize = $this->get_tile_size();

        $x = $x * $tilesize;
        $y = $y * $tilesize;

        $endx = min($width, $x + $tilesize);
        $endy = min($height, $y + $tilesize);

        $cropped = clone $image;
        $cropped->cropImage($endx - $x, $endy - $y, $x, $y);
        return $cropped;
    }

    /**
     * Crop the image to a particular tile with no cache.
     */
    protected function full_crop_tile($zoom, $x, $y) {
        $scaleinfo = $this->get_scale_info();
        list($width, $height) = $scaleinfo[$zoom];
        $zoomimage = $this->resize($width, $height);

        return $this->crop_tile($zoomimage, $zoom, $x, $y);
    }

    /**
     * Pre-processes a load of tiles.
     */
    public function preprocess() {
        global $CFG;

        $imageid = $this->get_image_id();
        $scaleinfo = $this->get_scale_info();
        foreach ($scaleinfo as $zoom => $size) {
            list($width, $height) = $scaleinfo[$zoom];
            $zoomimage = $this->resize($width, $height);

            list($x, $y) = $this->get_num_tiles($zoom);
            for ($i = 0; $i < $x; $i++) {
                for ($j = 0; $j < $y; $j++) {
                    $filename = $CFG->cachedir . "/tiles/{$imageid}/{$zoom}-{$i}-{$j}.jpg";
                    if (!file_exists($filename)) {
                        $image = $this->crop_tile($zoomimage, $zoom, $i, $j);
                        $this->save($image, $filename);
                        $image->clear();
                    }
                }
            }

            $zoomimage->clear();
        }
    }

    /**
     * Get the XML for this image.
     */
    public function get_xml() {
        global $CFG;

        // Generate scale info.
        $this->get_scale_info();

        list($width, $height) = $this->scaleorig;
        $maxdepth = $this->get_max_depth();
        list($x_tiles, $y_tiles) = $this->get_num_tiles($maxdepth - 1);
        $numtiles = $y_tiles * $x_tiles;
        return '<IMAGE_PROPERTIES WIDTH="' . $width . '" HEIGHT="' . $height . '" NUMTILES="' . $numtiles . '" NUMIMAGES="1" VERSION="1.8" TILESIZE="' . $CFG->tilesize . '" />';
    }

    /**
     * Set the tile we want.
     */
    public function output_tile($tile) {
        global $CFG;

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
            $image = $this->full_crop_tile($zoom, $x, $y);
        }

        $this->save($image, $cache);
        header('X-Sendfile: ' . $cache);
    }
}

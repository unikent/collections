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

class Processor
{
    private $processor;
    private $imageid;
    private $filename;
    private $scaleorig;
    private $scaleinfo;

    public function __construct($id) {
        $this->imageid = $id;
        $this->filename = \SCAPI\Models\File::get_path($id);
        $this->processor = new \Imagick($this->filename);

        if (\SCAPI\Models\File::get_extension($this->filename) == 'gif') {
            $this->processor = $this->processor->coalesceImages();
        }

        $this->set_scale_info();
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
     * Get the tilesize of the image.
     */
    private function get_tile_size() {
        global $CFG;
        return (float)$CFG->tilesize;
    }

    /**
     * Get the maximum depth of the image.
     */
    private function get_max_depth() {
        return count($this->scaleinfo);
    }

    /**
     * How many tiles should we split this into?
     */
    private function get_num_tiles($zoom = 0) {
        list($width, $height) = $this->scaleinfo[$zoom];
        $tilesize = $this->get_tile_size();

        $y_tiles = ceil($height / $tilesize);
        $x_tiles = ceil($width / $tilesize);

        return array($x_tiles, $y_tiles);
    }

    /**
     * Resize the image.
     */
    private function resize($targetWidth, $targetHeight) {
        $image = clone $this->processor;
        $image->resizeImage($targetWidth, $targetHeight, \Imagick::FILTER_POINT, 1, true); // FILTER_LANCZOS
        return $image;
    }

    /**
     * Get a thumbnail image.
     */
    private function get_thumbnail() {
        list($width, $height) = $this->scaleorig;
        $tilesize = $this->get_tile_size();

        $ratio = $width / $height;

        $targetWidth = $targetHeight = min($tilesize, max($width, $height));
        if ($ratio < 1) {
            $targetWidth = $targetHeight * $ratio;
        } else {
            $targetHeight = $targetWidth / $ratio;
        }

        return $this->resize($targetWidth, $targetHeight);
    }

    /**
     * Crop the image to a particular tile.
     */
    private function crop_tile($zoom, $x, $y) {
        list($width, $height) = $this->scaleinfo[$zoom];
        $image = $this->resize($width, $height);
        $tilesize = $this->get_tile_size();

        $x = $x * $tilesize;
        $y = $y * $tilesize;

        $endx = min($width, $x + $tilesize);
        $endy = min($height, $y + $tilesize);

        $new = clone $this->processor;
        $new->cropImage($endx - $x, $endy - $y, $x, $y);
        return $new;
    }

    /**
     * Pre-processes a load of tiles.
     */
    public function preprocess() {
        global $CFG;

        foreach ($this->scaleinfo as $zoom => $size) {
            list($x, $y) = $this->get_num_tiles($zoom);
            for ($i = 0; $i < $x; $i++) {
                for ($j = 0; $j < $y; $j++) {
                    $filename = $CFG->cachedir . "/{$this->imageid}-{$zoom}-{$i}-{$j}.jpg";
                    if (!file_exists($filename)) {
                        $image = $this->crop_tile($zoom, $i, $j);
                        $this->save($image, $filename);
                    }
                }
            }
        }
    }

    /**
     * Set the tile we want.
     */
    public function output_tile($tile) {
        global $CFG;

        header("Content-type: image/jpeg");

        // Does this file already exist in cache?
        $cache = $CFG->cachedir . "/{$this->imageid}-{$tile}.jpg";
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
     * Print to a browser.
     */
    public function output_as($landscape_width, $landscape_height, $portrait_width, $portrait_height, $quality = 100) {
        global $CFG;

        header("Content-type: image/jpeg");

        // Does this file already exist in cache?
        $cache = $CFG->cachedir . "/{$this->imageid}-{$landscape_width}-{$landscape_height}-{$portrait_width}-{$portrait_height}-{$quality}.jpg";
        if (file_exists($cache)) {
            header('X-Sendfile: ' . $cache);
            die;
        }

        $width = $landscape_width;
        $height = $landscape_height;

        if ($this->is_portrait()) {
            $width = $portrait_width;
            $height = $portrait_height;
        }

        $image = $this->constrained_resize($width, $height);

        $this->save($image, $cache, $quality);
        header('X-Sendfile: ' . $cache);
    }

    /**
     * Get the XML for this image.
     */
    public function get_xml() {
        global $CFG;

        list($width, $height) = $this->scaleorig;
        $maxdepth = $this->get_max_depth();
        list($x_tiles, $y_tiles) = $this->get_num_tiles($maxdepth - 1);
        $numtiles = $y_tiles * $x_tiles;
        return '<IMAGE_PROPERTIES WIDTH="' . $width . '" HEIGHT="' . $height . '" NUMTILES="' . $numtiles . '" NUMIMAGES="1" VERSION="1.8" TILESIZE="' . $CFG->tilesize . '" />';
    }

    public function get_width() {
        return (float)$this->processor->getImageWidth();
    }

    public function get_height() {
        return (float)$this->processor->getImageHeight();
    }

    /**
     * Constrained resize.
     */
    public function constrained_resize($targetWidth, $targetHeight) {
        $width = $this->get_width();
        $height = $this->get_height();

        // Calculate ratio.
        $ratio = $height / $width;
        if ($this->is_portrait()) {
            $ratio = $width / $height;
        }

        // Constrain.
        $targetWidth = min($targetWidth, $width);
        $targetHeight = min($targetHeight, $height);

        if ($targetWidth == 0) {
            $targetWidth = $targetHeight * $ratio;
        }

        if ($targetHeight == 0) {
            $targetHeight = $targetWidth * $ratio;
        }

        if ($targetWidth == $width && $targetHeight == $height) {
            return $this->processor;
        }

        return $this->resize($targetWidth, $targetHeight);
    }

    /**
     * Is this a landscape image?
     */
    public function is_landscape() {
        return $this->get_width() >= $this->get_height();
    }

    /**
     * Is this a portrait image?
     */
    public function is_portrait() {
        return !$this->is_landscape();
    }

    /**
     * Set quality of image.
     */
    private function set_quality($image, $quality) {
        if ($quality < 100) {
            $image->setCompression(\Imagick::COMPRESSION_JPEG); 
            $image->setCompressionQuality(100);
        }
    }

    /**
     * Save a given image.
     */
    public function save($image, $filename, $quality = 100) {
        $this->set_quality($image, $quality);
        $image->writeImage($filename);
    }

    /**
     * Print to a browser.
     */
    public function output($image, $quality = 100) {
        $this->set_quality($image, $quality);
        echo $image;
    }
}

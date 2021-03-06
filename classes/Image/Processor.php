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

    public function __construct($id) {
        $this->imageid = $id;
        $this->filename = \SCAPI\Models\File::get_path($id);
    }

    /**
     * Get the processor.
     */
    protected function get_processor($clone = false) {
        if (!isset($this->processor)) {
            $this->processor = new \Imagick($this->filename);

            if (\SCAPI\Models\File::get_extension($this->filename) == 'gif') {
                $this->processor = $this->processor->coalesceImages();
            }
        }

        if ($clone) {
            return clone $this->processor;
        }

        return $this->processor;
    }

    /**
     * Clean up.
     */
    public function clear() {
        if (isset($this->processor)) {
            $this->processor->clear();
        }
    }

    /**
     * Get the image ID.
     */
    protected function get_image_id() {
        return $this->imageid;
    }

    /**
     * Get the configured max tile size.
     */
    protected function get_tile_size() {
        global $CFG;
        return (float)$CFG->tilesize;
    }

    /**
     * Resize the image.
     */
    protected function resize($targetWidth, $targetHeight) {
        $image = $this->get_processor(true);
        $image->resizeImage($targetWidth, $targetHeight, \Imagick::FILTER_POINT, 1, true); // FILTER_LANCZOS
        return $image;
    }

    /**
     * Get a thumbnail image.
     */
    protected function get_thumbnail() {
        $width = $this->get_width();
        $height = $this->get_height();
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
     * Print to a browser.
     */
    public function output_as($landscape_width, $landscape_height, $portrait_width, $portrait_height, $quality = 100) {
        global $CFG;

        header("Content-type: image/jpeg");

        // Does this file already exist in cache?
        $dir = "{$CFG->cachedir}/tiles/{$this->imageid}";
        ensure_path_exists($dir);
        $cache = "{$dir}/{$landscape_width}-{$landscape_height}-{$portrait_width}-{$portrait_height}-{$quality}.jpg";
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
     * Width.
     */
    public function get_width() {
        $processor = $this->get_processor();
        return (float)$processor->getImageWidth();
    }

    /**
     * Height.
     */
    public function get_height() {
        $processor = $this->get_processor();
        return (float)$processor->getImageHeight();
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
            return $this->get_processor();
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

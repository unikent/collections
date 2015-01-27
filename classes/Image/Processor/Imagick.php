<?php
/**
 * Core Library.
 *
 * @package VERDI
 * @subpackage lib
 * @version 2.0
 * @author Skylar Kelty <S.Kelty@kent.ac.uk>
 * @copyright University of Kent
 */

namespace Verdi\Image\Processor;

defined("VERDI_INTERNAL") || die("This page cannot be accessed directly.");

class Imagick extends Processor
{
    public function __construct($filename) {
        $this->_image = new \Imagick($filename);

        if (get_file_extension($filename) == 'gif') {
            $this->_image = $this->_image->coalesceImages();
        }
    }

    /**
     * Resize the image.
     */
    public function resize($targetWidth, $targetHeight) {
        $image = clone $this->_image;
        $image->resizeImage($targetWidth, $targetHeight, \Imagick::FILTER_POINT, 1, true); // FILTER_LANCZOS
        return $image;
    }

    /**
     * Crop the image to a particular tile.
     */
    public function crop_tile($image, $x, $y, $x2, $y2) {
        $new = clone $image;
        $new->cropImage($x2 - $x, $y2 - $y, $x, $y);
        return $new;
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

    public function get_width() {
        return (float)$this->_image->getImageWidth();
    }

    public function get_height() {
        return (float)$this->_image->getImageHeight();
    }
}

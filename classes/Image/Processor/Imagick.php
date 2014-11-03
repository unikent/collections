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

namespace Image\Processor;

defined("VERDI_INTERNAL") || die("This page cannot be accessed directly.");

class Imagick extends Processor
{
    /** Base Image reference */
    private $_image;

    public function __construct($filename) {
        $this->_image = new \Imagick($filename);
    }

    /**
     * Resize the image.
     */
    public function resize($targetWidth, $targetHeight) {
        $image = $this->_image->clone();
        $image->resizeImage($targetWidth, $targetHeight, imagick::FILTER_POINT, 1, true);
        return $image;
    }

    /**
     * Crop the image to a particular tile.
     */
    public function crop_tile($image, $x, $y, $x2, $y2) {
        $new = $image->clone();
        $new->cropImage($x2 - $x, $y2 - $y, $x, $y);
        return $new;
    }

    /**
     * Save a given image.
     */
    public function save($image, $filename) {
        $image->writeImage($filename);
    }

    /**
     * Print to a browser.
     */
    public function output($image, $quality = 100) {
        if ($quality < 100) {
            $image->setCompression(\Imagick::COMPRESSION_JPEG); 
            $image->setCompressionQuality(100);
        }

        echo $image;
    }

    public function get_width() {
        return (float)$this->_image->getImageWidth();
    }

    public function get_height() {
        return (float)$this->_image->getImageHeight();
    }
}

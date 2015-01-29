<?php
/**
 * Core Library.
 *
 * @package SCAPI
 * @subpackage lib
 * @version 2.0
 * @author Skylar Kelty <S.Kelty@kent.ac.uk>
 * @copyright University of Kent
 */

namespace SCAPI\Image\Processor;

defined("SCAPI_INTERNAL") || die("This page cannot be accessed directly.");

/**
 * Note: GD doesnt support all of the required formats for SCAPI (tiff).
 */
class GD extends Processor
{
    public function __construct($filename) {
        $ext = get_file_extension($filename);
        switch ($ext) {
            case 'jpg':
            case 'jpeg':
                $this->_image = imagecreatefromjpeg($filename);
            break;

            case 'png':
                $this->_image = imagecreatefrompng($filename);
            break;

            case 'bmp':
                $this->_image = imagecreatefrombmp($filename);
            break;

            case 'gif':
                $this->_image = imagecreatefromgif($filename);
            break;
        }
    }

    /**
     * Resize the image.
     */
    public function resize($targetWidth, $targetHeight) {
        $width = $this->get_width();
        $height = $this->get_height();

        $image = imagecreatetruecolor($targetWidth, $targetHeight);
        imagecopyresampled($image, $this->_image, 0, 0, 0, 0, $targetWidth, $targetHeight, $width, $height);

        return $image;
    }

    /**
     * Crop the image to a particular tile.
     */
    public function crop_tile($image, $x, $y, $x2, $y2) {
        $new = imagecreatetruecolor($x2 - $x, $y2 - $y);
        imagecopy($new, $image, 0, 0, $x, $y, $x2 - $x, $y2 - $y);
        return $new;
    }

    /**
     * Save a given image.
     */
    public function save($image, $filename, $quality = 100) {
        imagejpeg($image, $filename, $quality);
    }

    /**
     * Print to a browser.
     */
    public function output($image, $quality = 100) {
        imagejpeg($image, NULL, $quality);
    }

    public function get_width() {
        return (float)imagesx($this->_image);
    }

    public function get_height() {
        return (float)imagesy($this->_image);
    }
}

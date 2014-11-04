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

/**
 * Defines a type of image processor.
 */
abstract class Processor
{
    /** Base Image reference */
    protected $_image;

    public abstract function __construct($filename);

    /**
     * Resize the image.
     */
    public abstract function resize($targetWidth, $targetHeight);

    /**
     * Crop the image to a particular tile.
     */
    public abstract function crop_tile($image, $x, $y, $x2, $y2);

    /**
     * Save the current image.
     */
    public abstract function save($image, $filename);

    /**
     * Print to a browser.
     */
    public abstract function output($image, $quality = 100);

    /**
     * Get the width of an image.
     */
    public abstract function get_width();

    /**
     * Get the height of an image.
     */
    public abstract function get_height();

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
     * Constrained resize.
     */
    public function constrained_resize($targetWidth, $targetHeight) {
        $width = $this->get_width();
        $height = $this->get_height();
        $ratio = $width / $height;

        if ($targetWidth == 0) {
            $targetWidth = $targetHeight * $ratio;
        }

        if ($targetHeight == 0) {
            $targetHeight = $targetWidth * $ratio;
        }

        $targetWidth = min($targetWidth, $width);
        $targetHeight = min($targetHeight, $height);

        if ($targetWidth == $width && $targetHeight == $height) {
            return $this->_image;
        }

        return $this->resize($targetWidth, $targetHeight);
    }
}

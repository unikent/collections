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
     * Print to a browser.
     */
    public function output_as($landscape_width, $landscape_height, $portrait_width, $portrait_height, $quality = 100) {
        $width = $landscape_width;
        $height = $landscape_height;

        if ($this->is_portrait()) {
            $width = $portrait_width;
            $height = $portrait_height;
        }

        $image = $this->resize($width, $height);

        $this->output($image, $quality);
    }
}

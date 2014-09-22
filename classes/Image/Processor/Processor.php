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
    public abstract function output($image);

    public abstract function get_width();
    public abstract function get_height();
}

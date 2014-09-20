<?php
/**
 * CLA system mock-up
 * 
 * @author Skylar Kelty <skylarkelty@gmail.com>
 */

namespace Presentation;

/**
 * Basic page methods class.
 */
class Page
{
	private $stylesheets;
	private $title;

	/**
	 * Constructor.
	 */
	public function __construct() {
		global $CFG;

		// Build a list of stylesheets.
		$this->stylesheets = array();
		$cssdir = substr($CFG->cssroot, strlen($CFG->dirroot) + 1);
		$list = scandir($CFG->cssroot);
		foreach($list as $filename) {
			if (strpos($filename, '.css') !== false) {
				$this->stylesheets[] = $cssdir . '/' . $filename;
			}
		}
	}

	/**
	 * Returns all stylesheets.
	 */
	public function get_stylesheets() {
		global $CFG;

		$str = '';
		foreach ($this->stylesheets as $sheet) {
			$sheet = $CFG->wwwroot . '/' . $sheet;
			$str .= "<link href=\"$sheet\" rel=\"stylesheet\">\n";
		}
		return $str;
	}

	/**
	 * Set the page title.
	 */
	public function set_title($title) {
		$this->title = $title;
	}

	/**
	 * Get the page title.
	 */
	public function get_title() {
		return $this->title;
	}
}

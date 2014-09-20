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
	private $navigation;
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

		// Build basic nav structure.
		$this->navigation = array(
			'Home' => '/'
		);
	}

	/**
	 * Returns true if the given url is the current page.
	 */
	public function is_active($url) {
		return true;
	}

	/**
	 * Returns pages in the nav bar.
	 */
	public function get_navbar() {
		return $this->navigation;
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

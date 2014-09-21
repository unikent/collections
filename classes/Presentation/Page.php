<?php
/**
 * Rapid Prototyping Framework in PHP.
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
	private $scripts;
	private $title;
	private $url;

	/**
	 * Constructor.
	 */
	public function __construct() {
		global $CFG;

		$this->title = '';
		$this->url = '/';
		$this->scripts = array();

		// Build a list of stylesheets.
		$this->stylesheets = array();
		$cssdir = substr($CFG->cssroot, strlen($CFG->dirroot) + 1);
		$list = scandir($CFG->cssroot);
		foreach ($list as $filename) {
			if (strpos($filename, '.css') !== false) {
				$this->require_css($cssdir . '/' . $filename);
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
		return $url == $this->url;
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

		$sheets = array_unique($this->stylesheets);
		$str = '';
		foreach ($sheets as $sheet) {
			$str .= "<link href=\"$sheet\" rel=\"stylesheet\">\n";
		}
		return $str;
	}

	/**
	 * Returns all javascript.
	 */
	public function get_javascript() {
		global $CFG;

		$scripts = array_unique($this->scripts);
		$str = '';
		foreach ($scripts as $script) {
			$str .= "<script src=\"$script\"></script>\n";
		}
		return $str;
	}

	/**
	 * Adds a Javascript to the page.
	 */
	public function require_js($path) {
		$url = new \URL($path);
		$this->scripts[] = $url->out();
	}

	/**
	 * Adds a Stylesheet to the page.
	 */
	public function require_css($path) {
		$url = new \URL($path);
		$this->stylesheets[] = $url->out();
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

	/**
	 * Set the page url.
	 */
	public function set_url($url) {
		$this->url = $url;
	}

	/**
	 * Get the page url relative to wwwroot.
	 */
	public function get_url() {
		return $this->url;
	}
}

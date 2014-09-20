<?php
/**
 * CLA system mock-up
 * 
 * @author Skylar Kelty <skylarkelty@gmail.com>
 */

namespace DML;

/**
 * Basic model class within DML layer.
 */
abstract class Model extends \Data\Model
{
	private $table;

	/**
	 * Constructor.
	 */
	public function __construct($tablename) {
		parent::__construct();

		$this->table = $tablename;
	}

	/**
	 * Get table name
	 */
	public function get_table() {
		return $this->table;
	}

	/**
	 * Convert an array to a model instance.
	 */
	public function bulk_set_data($array) {
		if (!is_array($array)) {
			$array = (array)$array;
		}

		foreach ($array as $key => $value) {
			$this->$key = $value;
		}
	}

	/**
	 * Searches a database for all currently known fields
	 * and if we get a single hit, we fill out our other
	 * fields and return true.
	 */
	public function extrapolate() {
		global $DB;

		try {
			$obj = $DB->get_record($this->table, (array)$this->get_data());
			$this->bulk_set_data($obj);
			return true;
		} catch (\Exception $e) {
			return false;
		}
	}
}
<?php
/**
 * CLA system mock-up
 * 
 * @author Skylar Kelty <skylarkelty@gmail.com>
 */

namespace DML;

class MySQLi extends \PDO
{
	/**
	 * Table prefix.
	 */
	private $prefix;

	/**
	 * Constructor
	 */
	public function __construct($hostname, $port, $dbname, $username, $password, $prefix) {
		parent::__construct("mysql:host=$hostname;port=$port;dbname=$dbname", $username, $password);
		$this->prefix = $prefix;
	}

	/**
	 * Get table name.
	 */
	private function get_table($name) {
		return $this->prefix . $name;
	}

	/**
	 * Get records from DB.
	 */
	public function get_records($table, $params = array()) {
		$exec = array();

		$sql = 'SELECT * FROM ' . $this->get_table($table);
		if (!empty($params)) {
			$sql .= ' WHERE';

			$where = array();
			foreach ($params as $k => $v) {
				$where[] = '`' . $k . '` = ?';
				$exec[] = $v;
			}

			$sql .= ' ' . implode(' AND ', $where);
		}

		$stmt = $this->prepare($sql);
		$stmt->execute($exec);

		$results = array();
		while (($obj = $stmt->fetchObject()) !== false) {
			$results[$obj->id] = $obj;
		}
		$stmt->closeCursor();

		return $results;
	}

	/**
	 * Get a record from DB.
	 */
	public function get_record($table, $params = array()) {
		$results = $this->get_records($table, $params);
		$count = count($results);

		if ($count > 1) {
			throw new \Exception('get_record() yielded multiple results!');
		}

		if ($count === 0) {
			return false;
		}

		return array_pop($results);
	}

	/**
	 * Get records from DB and convert them to models.
	 */
	public function get_models($model, $params = array()) {
		$model = '\\Models\\' . $model;
		$obj = new $model();
		$table = $obj->get_table();

		$data = $this->get_records($table, $params);

		$results = array();
		foreach ($data as $datum) {
			$obj = new $model();
			$obj->bulk_set_data($datum);
			$results[] = $obj;
		}

		return $results;
	}

	/**
	 * Get a record from DB and convert it to a model.
	 */
	public function get_model($model, $params = array()) {
		$results = $this->get_models($model, $params);
		$count = count($results);

		if ($count > 1) {
			throw new \Exception('get_model() yielded multiple results!');
		}

		if ($count === 0) {
			return false;
		}

		return array_pop($results);
	}
}

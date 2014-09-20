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
		return $this->prefix . $table;
	}

	/**
	 * Get a record from DB.
	 */
	public function get_record($table, $params = array()) {
		$exec = array();

		$sql = 'SELECT * FROM ' . $this->get_table($table);
		if (!empty($params)) {
			$sql .= ' WHERE';

			$where = array();
			foreach ($params as $k => $v) {
				$where[] = '? = ?';
				$exec[] = $k;
				$exec[] = $v;
			}

			$sql .= ' ' . implode(' AND ', $where);
		}

		$stmt = $this->prepare($sql);
		$stmt->execute($exec);
		return $stmt->fetchObject();
	}

	/**
	 * Get a record from DB and convert it to a model.
	 */
	public function get_model($model, $params = array()) {
		$model = '\\Models\\' . $model;
		$obj = new $model();
		$table = $obj->get_table();
		$data = $this->get_record($table, $params);
		$obj->bulk_set_data($data);

		return $obj;
	}
}

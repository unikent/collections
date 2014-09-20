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
	/**
	 * Convert an array to a model instance.
	 */
	public static function from_array($array) {
		$obj = new static();
		foreach ($array as $key => $value) {
			$obj->$key = $value;
		}

		return $obj;
	}

	/**
	 * Convert an object to a model instance.
	 */
	public static function from_object($object) {
		return static::from_array((array)$object);
	}
}
<?php
/**
 * CLA system mock-up
 * 
 * @author Skylar Kelty <skylarkelty@gmail.com>
 */

namespace Models;

class Extract extends \DML\Model
{
	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct('extract');

		$this->add_field('id', 			\Data\Model::TYPE_INT, 			11);
		$this->add_field('identifier', 	\Data\Model::TYPE_STRING, 		255);
		$this->add_field('start', 		\Data\Model::TYPE_INT, 			3);
		$this->add_field('end', 		\Data\Model::TYPE_INT, 			3);
		$this->add_field('created', 	\Data\Model::TYPE_TIMESTAMP, 	19);
		$this->add_field('updated', 	\Data\Model::TYPE_TIMESTAMP, 	19);
	}
}

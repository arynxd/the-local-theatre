<?php

namespace TLT\Model;

use TLT\Util\Data\Map;

//TODO: document fields in modules for better type information

/**
 * A model is a class to hold information about an entity
 *
 * Implementors should not add additional methods
 *
 * Implementors should only add a constructor which sets all public fields
 *
 * Implementors may choose to add a static factory, however this is not required
 */
abstract class Model {
	/**
	 * Converts this model to a Map for use in an API response
	 *
	 * @return Map a Map representing the JSON for this model
	 */
	abstract public function toMap();
}

<?php

namespace TLT\Repository;
use TLT\Request\Session;

/**
 * Base class for all repositories, these allow for interacting with the
 * DB in an abstract manor
 *
 * If the requested operation cannot be performed, implemetors must return null
 */
abstract class BaseRepository {
	/**
	 * @var Session $sess
	 */
	protected $sess;

	/**
	 * Constructs a new repository based on a Session
	 *
	 * @param Session $sess
	 */
	public function __construct($sess) {
		$this->sess = $sess;
	}

	/**
	 * Gets an entity by its ID from this repository
	 *
	 * @param string $id
	 * @return T|null
	 */
	abstract function get($id);

	/**
	 * Gets all the entities from this repository
	 *
	 * @return Map<T>
	 */
	abstract function getAll();

	/**
	 * Deletes an entity by its ID from this repository
	 *
	 * @return boolean True if any data was affected, false otherwise
	 */
	abstract function delete($id);

	/**
	 * Inserts a model into this repository
	 *
	 * @param T $model
	 * @return boolean True if any data was affected, false otherwise
	 */
	abstract function insert($model);

	/**
	 * Update an entity in this repository
	 *
	 * @param T $model
	 * @return boolean True of any data was affected, false otherwise
	 */
	abstract function edit($model);

	/**
	 * Determine whether an entity exists for the given id
	 *
	 * @param string $id
	 * @return boolean True if the entity exists, false otherwise
	 */
	abstract function exists($id);
}

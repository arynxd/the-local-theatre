<?php

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
     * Converts this model to JSON for use in an API response
     * 
     * @return Array an associative array representing the JSON for this model
     */
    public abstract function toJSON();
}
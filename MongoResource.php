<?php
class MongoResource {

  // Nobody should mess with these...
  private $collection = null;
  private $object = array();
  private $id = null;

  /** Construct the resource. */
  function __construct($collection, $params) {
    global $db;
    $this->collection = $db ? $db->{$collection} : null;
    $this->id = !empty($params['id']) ? $params['id'] : null;
  }

  /** Load this resource. */
  public function load() {

    // If the collection or id doesn't exist, then load nothing...
    if (empty($this->collection) || empty($this->id)) {
      return array();
    }

    // Get the object if it hasn't already been loaded.
    if (empty($this->object)) {
      $this->object = $this->collection->findOne(array('id' => $this->id));
    }

    // Return the object.
    return $this->object;
  }

  /** Saves this resource. */
  public function save($object) {

    // If the collection doesn't exist, then save nothing...
    if (empty($this->collection) || empty($object)) {
      return false;
    }

    // If the object already exists in mongo.
    if (!empty($this->object['_id'])) {

      // Save the object in mongo.
      $this->object = $this->collection->save($object);
    }
    else {

      // Insert the object in mongo.
      $this->object = $this->collection->insert($object);;
    }

    // Return that this object was saved.
    return true;
  }

  /** Deletes this resource. */
  public function delete() {

    // If the collection doesn't exist, then save nothing...
    if (empty($this->collection) || empty($this->id)) {
      return false;
    }

    // Remove the object from mongo.
    $this->collection->remove(array('id' => $this->id));
    return true;
  }
}
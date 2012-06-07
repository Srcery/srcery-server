<?php
class Resource {

  /** The ID of this resource. */
  public $id = null;

  // Nobody should mess with these...
  protected $db = null;
  protected $collection = null;
  protected $object = null;

  // Construct the resource.
  function __construct($id = null) {
    global $srcery_db;
    $this->db = $srcery_db;
    $this->collection = $this->db->resources;
    $this->id = $id;
    $this->object = $this->_load();
  }

  /**
   * Loads the resource.
   * @return type
   */
  private function _load() {
    $object = array();
    if ($this->id) {
      if ($object = $this->collection->findOne(array('id' => $this->id))) {
        array_walk($object, function($value, $key, $resource) {
          if (isset($resource->{$key})) {
            $resource->{$key} = $value;
          }
        }, $this);
      }
    }

    // Allow classes to load.
    $this->load();
    return $object;
  }

  /**
   * Load objects, etc...
   */
  public function load() {
  }

  /**
   * Returns all the data we wish to store in the database.
   * @return type
   */
  function get() {
    return array(
      'id' => $this->id
    );
  }

  /**
   * Private function to merge the actual values into the object.
   * @return type
   */
  private function _get() {
    $object = $this->object ? $this->object : array();
    return array_merge($object, $this->get());
  }

  /**
   * Saves the resource.
   * @return type
   */
  function save() {

    // Get the object to save.
    $object = $this->_get();

    // If the object exists...
    if (!empty($this->object['_id'])) {

      // Save the object back to the collection.
      $this->object = $this->collection->save($object);
    }
    else {

      // Otherwise, we need to insert it.
      $this->object = $this->collection->insert($object);
    }
  }

  /**
   * Handles a request.
   * @param type $request
   * @return type
   */
  public function handleRequest($request) {
    return array();
  }
}

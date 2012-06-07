<?php
class Instance extends Resource {

  // Each instance has a reference to a derivative.
  public $derivative = null;

  // Each instance has a type.
  public $type = '';

  function __construct($id, $params) {
    parent::__construct($id);
    $this->type = $params['type'];
    switch ($this->type) {

    }
  }

  /**
   * Loads a new instance.
   */
  function load() {
    parent::load();

    // Turn the derivative into an object.
    $this->derivative = new Derivative($this->derivative);
  }

  /**
   * Returns the object to save in database.
   */
  function get() {
    return array_merge(parent::get(), array(
      'derivative' => $this->derivative->id,
      'type' => $this->type,
    ));
  }
}
?>
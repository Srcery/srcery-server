<?php
class Derivative extends Resource {
  public $width = 0;
  public $height = 0;

  function __construct($id, $params) {
    parent::__construct($id);
    $this->type = $params['type'];
    switch ($this->type) {

    }
  }

  /**
   * Returns the object to save in database.
   */
  function get() {
    return array_merge(parent::get(), array(
      'width' => $this->width,
      'height' => $this->height,
    ));
  }
}
?>

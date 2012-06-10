<?php
class Instance extends Resource {

  /** @see Derivative */
  public $derivative = null;

  function set($params) {
    parent::set($params);
    $derivative = !empty($params['derivative']) ? $params['derivative'] : array();
    $this->derivative = new Derivative($derivative);
  }

  function get() {
    // Return this object plus the parameters.
    return array_merge(parent::get(), array(
      'derivative' => $this->derivative->get(),
    ));
  }

  function save() {
    $this->derivative->save();
    return parent::save();
  }
}
?>
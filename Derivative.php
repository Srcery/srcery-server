<?php
class Derivative extends Resource {
  public $width = 0;
  public $height = 0;

  /** @see Image */
  public $image = null;

  function set($params) {
    parent::set($params);
    $this->width = !empty($params['width']) ? $params['width'] : 0;
    $this->height = !empty($params['height']) ? $params['height'] : 0;
    $image = !empty($params['image']) ? $params['image'] : array();
    $this->image = new Image($image);
  }

  function get() {
    return array_merge(parent::get(), array(
      'width' => $this->width,
      'height' => $this->height,
      'image' => $this->image->get(),
    ));
  }

  function save() {
    $this->image->save();
    return parent::save();
  }
}
?>

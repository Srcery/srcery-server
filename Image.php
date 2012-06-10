<?php
class Image extends File {
  protected function folder() {
    return 'images';
  }

  protected function place_holder() {
    return 'placeholder.png';
  }

  protected function allowed_extensions() {
    return array('jpg', 'jpeg', 'png', 'gif');
  }

  protected function post_name() {
    return 'img';
  }
}

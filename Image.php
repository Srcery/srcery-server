<?php
class Image extends File {
  public function getFolder() {
    return 'images';
  }

  public function getPlaceHolder() {
    return 'placeholder.png';
  }

  public function allowedExtensions() {
    return array('jpg', 'jpeg', 'png', 'gif');
  }

  public function postName() {
    return 'img';
  }
}

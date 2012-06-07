<?php
class File extends Resource {
  function __construct($id = null) {
    parent::__construct($id);
  }

  protected function get_extension() {
    return strtolower(substr($file, strrpos($file, '.') + 1));
  }

  public function handleRequest($request) {
    parent::handleRequest($request);
    if ($request->method == 'get') {
      return $this->getFile();
    }
    else if ($request->method == 'post') {
      return $this->setFile($request->args);
    }

    // Return an error.
    return new Response(406);
  }

  public function getPath() {
    return $this->getFolder() . '/' . $this->id;
  }

  /**
   * Get the file.
   * @return Response
   */
  public function getFile() {

    // Get the file path.
    $file = $this->getPath();
    $save = true;

    // If the file doesn't exist, then get the placeholder.
    if (!file_exists($file)) {
      $file = $this->getFolder() . '/' . $this->getPlaceHolder();
      $save = false;
    }

    // If the file exists, then stream it to the browser.
    if (file_exists($file) && ($fp = fopen($file, 'rb'))) {

      // Make sure we have an entry in the db for this resource.
      if ($save && empty($this->object['_id'])) {
        $this->save();
      }

      $response = new Response(200, array(), array(
        'Content-Type: image/png',
        "Content-Length: " . filesize($file)
      ));
      fpassthru($fp);
      fclose($fp);
    }
    else {
      $response = new Response(404);
    }

    return $response;
  }

  /**
   * Set the file.
   * @return Response
   */
  public function setFile() {

    // The allowed extensions.
    $allowed_ext = $this->allowedExtensions();

    // Get the post name of the file.
    $post_name = $this->postName();

    // See if our image upload exists.
    if (array_key_exists($post_name, $_FILES) && $_FILES[$post_name]['error'] == 0) {

      $file = $this->getPath();

      // Get the upload.
      $new_file = $_FILES[$post_name];

      // Check to see if this image has the extensions allowed.
      if (!in_array(get_file_ext($new_file['name']), $allowed_ext)) {
        return new Response(406, 'Only ' . implode(',', $allowed_ext) . ' files are allowed!');
      }

      // For now just delete the old file.
      if (file_exists($file)) {
        unlink($file);
      }

      // Now move the image upload to the upload directory.
      if (move_uploaded_file($new_file['tmp_name'], $file)) {
        return new Response(200, array('id' => $this->id));
      }
    }

    // Return a 406 error.
    return new Response(406);
  }

  public function getFolder() {
    return 'files';
  }

  public function getPlaceHolder() {
    return '';
  }

  public function getPostName() {
    return 'file';
  }
}
?>
<?php
class Response {
  private $data = array();
  function __construct($code = 200, $data = null, $headers = array()) {
    $this->data = $data;
    switch ($code) {
      case 200:
        header('HTTP/1.1 200 OK', true, $code);
        break;
      case 404:
        header('HTTP/1.1 404 Not Found', true, $code);
        break;
      case 406:
        header('HTTP/1.1 406 Not Acceptable', true, $code);
        break;
    }

    // Iterate over all the headers.
    foreach ($headers as $header) {
      header($header);
    }
  }

  public function response() {
    if (!empty($this->data)) {
      header('Content-type: application/json');
      echo json_encode($this->data);
    }
  }
}
?>
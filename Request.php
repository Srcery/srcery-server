<?php
class Request {
  public $args = array();
  public $method = '';
  function __construct() {
    $this->request = parse_url(ltrim(strtolower(str_replace($_SERVER['SCRIPT_NAME'], '', $_SERVER['REQUEST_URI'])), '/'));
    $this->path = explode('/', !empty($this->request['path']) ? $this->request['path'] : '');
    $this->method = strtolower($_SERVER['REQUEST_METHOD']);
    $this->args = $this->get_args();
  }

  /**
   * Get the arguments for the request.
   * @return array
   */
  private function get_args() {
    $args = array();
    switch ($this->method) {
      case 'get':
        parse_str($this->request['query'], $args);
        break;
      case 'post':
        $args = $_POST;
        break;
      case 'put':
        parse_str(file_get_contents('php://input'), $args);
        break;
      case 'delete':
        parse_str(file_get_contents('php://input'), $args);
        break;
    }
    return $args;
  }

  /**
   * Handle the request made.
   */
  public function handleRequest() {
    $resource = null;

    // Always return 200 for options requests...
    if ($this->method == 'options') {
      return new Response(200);
    }

    // Make sure they provide a type...
    if (!empty($this->path[0])) {

      // Get the params.
      $params = $this->args;

      // If the ID is provided from the path.
      if (!empty($this->path[1])) {
        $params['id'] = $this->path[1];
      }

      switch ($this->path[0]) {
        case 'img':
          $resource = new Image($params);
          break;
        case 'inst':
          $resource = new Instance($params);
          break;
        case 'der':
          $resource = new Derivative($params);
          break;
      }
    }

    // Return the resource or 406 if no resource exists...
    if ($resource) {
      return $resource->handleRequest($this);
    }
    else {
      return new Response(406, 'Unknown resource.');
    }
  }
}
?>
<?php
class Request {
  public $path = array();
  public $method = '';
  public $query = '';
  public $args = array();

  function __construct() {
    $request = explode('/', $_SERVER['REQUEST_URI']);
    $scriptName = explode('/', $_SERVER['SCRIPT_NAME']);
    $this->path = array_map('strtolower', array_values(array_diff($request, $scriptName)));
    $this->method = strtolower($_SERVER['REQUEST_METHOD']);
    $this->query = parse_url(strtolower($_SERVER['REQUEST_URI']));
    $this->query = !empty($this->query['query']) ? $this->query['query'] : '';
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
        if ($this->query) {
          parse_str($this->query, $args);
        }
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
<?php
class Request {
  public $args = array();
  public $method = '';
  function __construct() {
    $request = explode('/', $_SERVER['REQUEST_URI']);
    $scriptName = explode('/', $_SERVER['SCRIPT_NAME']);
    $this->args = array_map('strtolower', array_values(array_diff($request, $scriptName)));;
    $this->method = strtolower($_SERVER['REQUEST_METHOD']);
  }

  /**
   * Handle the request made.
   */
  public function handleRequest() {
    $resource = null;

    if ($this->method == 'options') {
      return new Response(200);
    }

    // Make sure we have two arguments...
    if (!empty($this->args[0]) && !empty($this->args[1])) {
      switch ($this->args[0]) {
        case 'img':
          $resource = new Image($this->args[1]);
          break;
        case 'inst':
          $resource = new Instance($this->args[1]);
          break;
      }
    }

    // Return the resource or 406 if no resource exists...
    return $resource ? $resource->handleRequest($this) : new Response(406);
  }
}
?>
<?php
// Autoload classes.
spl_autoload_register(function ($class) {
  include $class . '.php';
});

// Get a mongodb instance and point it to the srcery db.
$mongo = new Mongo('localhost');
$db = $mongo->srcery;

// Get the request object.
$request = new Request();

// Handle the request.
$response = $request->handleRequest();

// Echo the response.
$response->response();

// Close mongo.
$mongo->close();

// Exit.
exit;
?>

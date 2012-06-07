<?php
// Autoload classes.
spl_autoload_register(function ($class) {
  include $class . '.php';
});

// Get a mongodb instance.
$mongo = new Mongo('localhost');
$srcery_db = $mongo->selectDB('srcery');

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

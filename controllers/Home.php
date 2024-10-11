<?php
class Home
{
  public function index()
  {
    echo json_encode([
      "code" => 200,
      "status" => true,
      "error" => false,
      "success" => true,
      "path" => $_SERVER["REQUEST_URI"],
      "method" => $_SERVER["REQUEST_METHOD"],
      "server" => $_SERVER["SERVER_NAME"],
      "port" => $_SERVER["SERVER_PORT"],
      "remote_port" => $_SERVER["REMOTE_PORT"],
      "message" => "This is index route",
    ]);
  }
}

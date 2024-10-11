<?php
// Require The Router classs Here
require_once __DIR__ . "/assets/router/Router.php";
// Require The Databases Class Here
require_once __DIR__ . "/assets/databases/Databases.php";
// Set The Controllers Path Here
$router->set_controller("/controllers/");
// Setup Database Coonections Here
//$DB->create_connection();
// Check If The Database Connected
// $DB->is_connected();
// Defined Some Routes Here
// Defined GET Routes Here
$router->get("/", "Home@index");
$router->get("/user/name?name=", "User@index");
$router->post("/user/signup", "User@signup");

?>

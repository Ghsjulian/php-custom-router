<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header(
  "Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With"
);

class Router
{
  private $route_list = [];
  private $myControllers = [];
  private $root_path = __DIR__ . "/../../";
  private $controller_path = "";
  private $dev = "__ghs__julian__";
  private $status = false;

  public function __construct()
  {
    $data = file_get_contents(__DIR__ . "/../config/01.txt");
    if ($data == "DO_NOT_DELETE_THIS_FILE") {
      $this->status = true;
    } else {
      echo json_encode([
        "code" => 403,
        "type" => "error",
        "status" => "false",
        "message" => "Access Denied !",
      ]);
      exit();
    }
  }

  private function addRoute($method, $path, $controller)
  {
    $req_path = "";
    $main_path = explode("?", $path);
    if (count($main_path) == 2) {
      $req_path = $main_path[0];
    } else {
      $req_path = $path;
    }
    $this->route_list[] = [
      "method" => $method,
      "path" => trim($req_path),
      "controller" => trim($controller),
    ];
  }

  public function set_controller($path = null)
  {
    if ($path == null) {
      echo json_encode([
        "code" => 403,
        "error" => true,
        "status" => false,
        "message" => "Please Set Your Controller Path !",
      ]);
      exit();
    } else {
      $this->controller_path = $path;
    }
  }
  public function readDir()
  {
    if (is_dir($this->root_path . $this->controller_path)) {
      if ($dir = opendir($this->root_path . $this->controller_path)) {
        while (($file = readdir($dir)) !== false) {
          if ($file !== "." && $file !== "..") {
            array_push($this->myControllers, $file);
          }
        }
        closedir($dir);
      }
    }
  }

  public function get($path, $controller)
  {
    $this->addRoute("GET", $path, $controller);
  }

  public function post($path, $controller)
  {
    $this->addRoute("POST", $path, $controller);
  }

  public function put($path, $controller)
  {
    $this->addRoute("PUT", $path, $controller);
  }

  public function delete($path, $controller)
  {
    $this->addRoute("DELETE", $path, $controller);
  }

  public function __destruct()
  {
    $req_method = $_SERVER["REQUEST_METHOD"];
    $req_path = "";
    $params = $_GET;
    $main_path = explode("?", $_SERVER["REQUEST_URI"]);
    if (count($main_path) == 2) {
      $req_path = $main_path[0];
    } else {
      $req_path = $_SERVER["REQUEST_URI"];
    }

    foreach ($this->route_list as $route) {
      if ($route["method"] === $req_method && $route["path"] === $req_path) {
        $main = explode("@", $route["controller"]);
        $controller = $main[0];
        $action = $main[1];
        // Call The Controller
        if ($controller && $action) {
          $this->readDir();
          foreach ($this->myControllers as $file) {
            require_once $this->root_path . $this->controller_path . $file;
          }
          $controller = new $controller();
          call_user_func_array([$controller, $action], [$params]);
          exit();
        }
      }
    }
  }
}
// how To Use The Router Class In Your index.php
$router = new Router();
/*
$router = new Router();
$router->setbaseDir("/");
$router->get("/", "Home@index");
$router->get("/user", "Home@index");
$router->get("/about", "Home@index");
$router->get("/contact", "Home@index");
$router->run();
*/
?>

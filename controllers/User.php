<?php
require_once __DIR__ . "/../assets/databases/Databases.php";
class User extends __database__
{
  public function index($data)
  {
    print_r($data);
    echo "Hello " . $data["name"];
  }
  public function signup()
  {
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    if ($requestMethod === "POST") {
      $data = json_decode(file_get_contents("php://input"), true);
      $this->saveUser($data);
    } else {
      echo json_encode([
        "code" => 403,
        "type" => false,
        "error" => true,
        "status" => false,
        "message" => "Invalid Reauest Method!",
      ]);
    }
  }
  private function saveUser($data = null)
  {
    if ($data !== null) {
      foreach ($data as $key => $value) {
        if ($data[$key] === "" || $data[$key] == null) {
          echo json_encode([
            "code" => 403,
            "type" => false,
            "error" => true,
            "status" => false,
            "lock" => $key,
            "message" => $key . " Is Required!",
          ]);
          exit();
        }
      }
      $token = $this->__encode_jwt__([
          $data["user_name"],
          $data["user_email"]
          ]);
      $data["user_token"] = $token;
      $regi = $this->create_one("users", $data);
      if ($regi) {
        echo json_encode([
          "code" => 201,
          "type" => true,
          "error" => false,
          "status" => true,
          "token" => $token,
          "message" => "User Registration Successfully!",
        ]);
        exit();
      } else {
        echo json_encode([
          "code" => 403,
          "type" => false,
          "error" => true,
          "status" => false,
          "message" => "User Registration Failed !",
        ]);
        exit();
      }
    } else {
      return 0;
    }
  }
}

?>

<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header(
  "Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With"
);

/*_________ __ghs__ _________*/
class __database__
{
  private $conn = false;
  private $result;
  private $secret_key = "__ghs__julian__";
  private $host = "localhost";
  private $user = "root";
  private $password = "";
  private $db_name = "blog";
  private $main_dir = __DIR__ . "/../assets";

  public function __construct()
  {
    if (!$this->conn) {
      $connect = new mysqli(
        $this->host,
        $this->user,
        $this->password,
        $this->db_name
      );
      if ($connect->connect_error) {
        return $connect->connect_error;
      } else {
        $this->conn = $connect;
      }
    }
  }

  public function create_connection()
  {
    if (!$this->conn) {
      $this->conn();
    } else {
      return $this->conn;
    }
  }
  /* For Checking If Connect Or Not */
  public function is_connected()
  {
    if ($this->conn) {
      echo json_encode([
        "code" => 200,
        "error" => false,
        "success" => true,
        "status" => true,
        "db_name" => $this->db_name,
        "db_host" => $this->host,
        "db_user" => $this->user,
        "db_password" => $this->password,
        "is_connect" => true,
        "message" => "Database Connected Successfully!",
      ]);
      exit();
    } else {
      echo json_encode([
        "code" => 403,
        "error" => true,
        "success" => false,
        "status" => false,
        "db_name" => $this->db_name,
        "db_host" => $this->host,
        "db_user" => $this->user,
        "db_password" => $this->password,
        "is_connect" => false,
        "message" => "Database Connected Failed!",
      ]);
      exit();
    }
  }

  public function create_sql($arr = null)
  {
    $column = "";
    $values = "";
    if ($arr != null) {
      foreach ($arr as $key => $value) {
        $column .= "`$key`" . ",";
        $values .= "'$value'" . ",";
      }
      $filter_col = trim($column, ",");
      $filter_val = trim($values, ",");
      return [$filter_col, $filter_val];
    } else {
      return null;
    }
  }

  public function create_one($table = null, $arr = null)
  {
    if ($table != null && $arr != null) {
      $sql_data = $this->create_sql($arr);
      $sql = "INSERT INTO $table ($sql_data[0])VALUES($sql_data[1])";
      $query = mysqli_query($this->conn, trim($sql));
      if ($query) {
        return true;
      } else {
        return false;
      }
    } else {
      return null;
    }
  }

  public function img_to_base64($imagePath)
  {
    $imageData = file_get_contents($imagePath);
    return base64_encode($imageData);
  }

  public function base64_to_binary($base64String)
  {
    $imageData = base64_decode($base64String);
    return $imageData;
  }

  public function binary_to_base64($binaryData)
  {
    return base64_encode($binaryData);
  }

  public function binary_to_img($binaryData, $outputPath)
  {
    file_put_contents($outputPath, $binaryData);
  }

  public function __scape__($value)
  {
    return mysqli_real_escape_string($this->conn, $value);
  }
  /*    OTHER USEFUL METHODS    */
  /*    Creating Random String  */
  public function __get_string__($len = 8)
  {
    $random = openssl_random_pseudo_bytes($len);
    $hex = bin2hex($random);
    $string = substr($hex, 0, $len);
    return "__ghs__" . $string;
  }
  /* Creating Random Number Generator */
  public function __get_number__($len = 5)
  {
    $random = openssl_random_pseudo_bytes($len);
    $number = 0;
    for ($i = 0; $i < $len; $i++) {
      $number = $number * 10 + (ord($random[$i]) % 10);
    }
    return $number;
  }

  /*   Get Data Type   */
  public function __get_type__($var)
  {
    if (is_array($var)) {
      return "array";
    } elseif (is_object($var)) {
      return "object";
    } elseif (is_int($var)) {
      return "integer";
    } elseif (is_float($var)) {
      return "float";
    } elseif (is_string($var)) {
      return "string";
    } elseif (is_bool($var)) {
      return "boolean";
    } elseif (is_null($var)) {
      return "null";
    } elseif (is_resource($var)) {
      return "resource";
    } else {
      return "unknown";
    }
  }
  /*     Encrypt Any String    */
  public function __encrypt__($value)
  {
    if ($this->__get_type__($value) == "string") {
      $iv = openssl_random_pseudo_bytes(
        openssl_cipher_iv_length("aes-256-cbc")
      );
      $encrypted = openssl_encrypt(
        $value,
        "aes-256-cbc",
        $this->secret_key,
        0,
        $iv
      );
      return base64_encode($iv . $encrypted);
    } else {
      return "Send A String Which You Want To Encrypt.";
    }
  }
  /* Decrypt The Encrypted String  */

  public function __decrypt__($encrypted)
  {
    if ($this->__get_type__($encrypted) == "string") {
      $decoded = base64_decode($encrypted);
      $iv = substr($decoded, 0, openssl_cipher_iv_length("aes-256-cbc"));
      $encrypted = substr($decoded, openssl_cipher_iv_length("aes-256-cbc"));
      return openssl_decrypt(
        $encrypted,
        "aes-256-cbc",
        $this->secret_key,
        0,
        $iv
      );
    } else {
      return "Send A Encrypted String Which You Want To Decrypt.";
    }
  }
  /* Save Base64 Image String As Image */
  public function __save_image__($image, $img_name)
  {
    if ($image && $img_name) {
      $imageData = base64_decode(
        preg_replace("#^data:image/\w+;base64,#i", "", $image)
      );
      $path = __DIR__ . "/images/";
      try {
        if (!file_exists($path)) {
          mkdir($path, 0777, true);
        }
        if (file_put_contents($path . $img_name, $imageData)) {
          $upload_file = $path . $img_name;
          $image_size = getimagesize($upload_file);
          $image_size_in_bytes = $image_size[0] * $image_size[1] * 3;
          if ($image_size_in_bytes > 2000000) {
            unlink($upload_file);
            return false;
          } else {
            return true;
          }
        } else {
          return false;
        }
      } catch (Exception $e) {
        return $e;
      }
    } else {
      return "First Argument Image File Strings And Second Argument The Name Of The Image Which You Want To Save As.";
    }
  }
  /* CREATE JSON WEB TOKEN */
  public function __encode_jwt__($payload)
  {
    $header = [
      "alg" => "HS256",
      "typ" => "JWT",
    ];
    $headerEncoded = rtrim(
      strtr(base64_encode(json_encode($header)), "+/", "-_"),
      "="
    );
    // Payload Encoded
    $payloadEncoded = rtrim(
      strtr(base64_encode(json_encode($payload)), "+/", "-_"),
      "="
    );
    // Generate the signature
    $has_key = $headerEncoded . "." . $payloadEncoded . $this->secret_key;
    $signature = rtrim(
      strtr(base64_encode(hash_hmac("sha256", $has_key, true)), "+/", "-_"),
      "="
    );
    $JWT = $headerEncoded . "." . $payloadEncoded . "." . $signature;
    return $JWT;
  }

  public function __decode_jwt__($jwt)
  {
    // Split the JWT into its three parts
    $parts = explode(".", $jwt);
    // Decode the header and payload
    $header = json_decode(base64_decode(strtr($parts[0], "-_", "+/")), true);
    $payload = json_decode(base64_decode(strtr($parts[1], "-_", "+/")), true);

    $has_key = $parts[0] . "." . $parts[1] . $this->secret_key;
    // Verify the signature
    $signature = hash_hmac("sha256", $has_key, true);
    $signature = rtrim(strtr(base64_encode($signature), "+/", "-_"), "=");

    // Check if the signature is valid
    if ($signature === $parts[2]) {
      // Output the decoded JWT
      return $payload;
    } else {
      echo "Invalid signature";
    }
  }
  public function __validate__($arr)
  {
    if ($this->__get_type__($arr) == "array") {
      $result = [];
      foreach ($arr as $key => $value) {
        if (trim($arr[$key]) == "") {
          array_push($result, $key . " Is Required!");
        }
      }
      if (count($result) > 0) {
        echo json_encode([
          "code" => 403,
          "status" => "failed",
          "message" => $result,
        ]);
      } else {
        return true;
      }
    } else {
      return "The Argument Required An Array !";
    }
  }
  function get_all_dir($directory)
  {
    $files = scandir($directory);
    foreach ($files as $file) {
      if ($file !== "." && $file !== "..") {
        $path = $directory . "/" . $file;
        if (is_file($path)) {
          yield $path;
        }
      }
    }
    $directory = "path/to/directory"; // Replace with the actual directory path
    $fileGenerator = iterateFiles($directory);
    foreach ($fileGenerator as $file) {
      echo "File: $file\n";
    }
  }
}

$DB = new __database__();
?>

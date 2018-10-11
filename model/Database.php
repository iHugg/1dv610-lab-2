<?php
namespace model;

class Database {
  private $settings;
  private $connection;

  public function __construct() {
    $this->settings = parse_ini_file("./settings.ini");
    $this->connection = new \mysqli($this->settings["dbURL"], $this->settings["dbName"], $this->settings["dbPassword"], $this->settings["dbName"]);
  }

  public function __destruct() {
    $this->connection->close();
  }

  public function userExists(string $username) : bool {
    return $this->connection->query('SELECT username from users WHERE username = "' . $username . '"')->num_rows > 0;
  }

  public function getHashedPassword(string $username) : string {
    $result = $this->connection->query('SELECT password from users WHERE username = "' . $username . '"');
    
    if ($result->num_rows == 1) {
      return $result->fetch_assoc()["password"];
    }

    return "";
  }

  public function addUserToDatabase(string $username, string $password) : bool {
    $sql = 'INSERT INTO users (username, password) VALUES ("' . $username . '", "' . password_hash($password, PASSWORD_BCRYPT) . '")';
    return $this->connection->query($sql);
  }
}
?>
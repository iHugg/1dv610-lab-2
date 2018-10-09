<?php
namespace model;

class Database {
  private $settings;
  private $connection;

  public function __construct() {
    $this->settings = parse_ini_file("./settings.ini");
    $this->connection = new \mysqli($this->settings["dbURL"], $this->settings["dbName"], $this->settings["dbPassword"], $this->settings["dbName"]);
  }

  private function __destruct() {
    $this->connection->close();
  }

  public function userExists(string $username) : bool {
    $result = $this->connection->query('SELECT username from users WHERE username = "' . $username . '"');

    if ($result->num_rows > 0) {
      return true;
    }

    return false;
  }
}
?>
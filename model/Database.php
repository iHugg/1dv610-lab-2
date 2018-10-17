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

  public function getConnection() : \mysqli {
    return $this->connection;
  }
}
?>
<?php
namespace model;

class ThreadDatabase {
  private $settings;
  private $connection;

  public function __construct() {
    $this->settings = parse_ini_file("./settings.ini");
    $this->connection = new \mysqli($this->settings["dbURL"], $this->settings["dbName"], $this->settings["dbPassword"], $this->settings["dbName"]);
  }

  public function __destruct() {
    $this->connection->close();
  }

  public function saveThread(string $threadTitle) {
    $sql = 'INSERT INTO threads (title) VALUES ("' . $threadTitle . '")';
    return $this->connection->query($sql);
  }
}
?>
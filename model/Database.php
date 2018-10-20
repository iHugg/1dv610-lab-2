<?php
namespace model;

/**
 * Connects to the database.
 * ONLY CREATE ONE INSTANCE OF THIS CLASS.
 * If more are created it will slow the entire program down significantly.
 */
class Database {
  private $settings;
  private $connection;

  public function __construct() {
    $this->settings = parse_ini_file("./settings.ini");
    $this->connection = new \mysqli($this->settings["dbURL"], $this->settings["dbName"], $this->settings["dbPassword"], $this->settings["dbName"]);
  }

  /**
   * Not entirely sure if this will work but it makes sense for it to work.
   */
  public function __destruct() {
    $this->connection->close();
  }

  public function getConnection() : \mysqli {
    return $this->connection;
  }
}
?>
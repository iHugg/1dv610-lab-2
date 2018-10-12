<?php
namespace model;

class BrowserDatabase {
  private $settings;
  private $connection;

  public function __construct() {
    $this->settings = parse_ini_file("./settings.ini");
    $this->connection = new \mysqli($this->settings["dbURL"], $this->settings["dbName"], $this->settings["dbPassword"], $this->settings["dbName"]);
  }

  public function __destruct() {
    $this->connection->close();
  }

  public function saveBrowser(string $browser, string $hashedPassword) {
    $sql = 'REPLACE INTO browsers (browserName, passwordCookie) VALUES ("' . $browser . '", "' . $hashedPassword . '")';
    $this->connection->query($sql);
  }

  public function getCookiePassword(string $browser) : string {
    $result = $this->connection->query('SELECT passwordCookie from browsers WHERE browserName = "' . $browser . '"');

    if ($result->num_rows == 1) {
      return $result->fetch_assoc()["passwordCookie"];
    }

    return "";
  }
}
?>
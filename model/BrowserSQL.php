<?php
namespace model;

class BrowserSQL {
  private $connection;

  public function __construct(\mysqli $connection) {
    $this->connection = $connection;
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
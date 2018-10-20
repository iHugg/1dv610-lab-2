<?php
namespace model;

class BrowserSQL {
  private static $tableName = "browsers";
  private static $browserName = "browserName";
  private static $passwordCookie = "passwordCookie";
  private $connection;

  public function __construct(\mysqli $connection) {
    $this->connection = $connection;
  }

  public function saveBrowser(string $browser, string $hashedPassword) {
    $sql = 'UPDATE ' . self::$tableName . ' SET ' . self::$passwordCookie . '="' . $hashedPassword . '" 
    WHERE ' . self::$browserName . '="' . $browser . '"';
    $this->connection->query($sql);
  }

  public function getCookiePassword(string $browser) : string {
    $result = $this->connection->query('SELECT ' . self::$passwordCookie . ' FROM ' . self::$tableName . ' WHERE 
    ' . self::$browserName . '="' . $browser . '"');

    if ($result->num_rows == 1) {
      $result = $result->fetch_assoc();
      return $result[self::$passwordCookie];
    }

    return "";
  }
}
?>
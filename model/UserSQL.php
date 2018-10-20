<?php
namespace model;

class UserSQL {
  private static $tableName = "users";
  private static $username = "username";
  private static $password = "password";
  private $connection;

  public function __construct(\mysqli $connection) {
    $this->connection = $connection;
  }
  public function userExists(string $username) : bool {
    $result = $this->connection->query('SELECT ' . self::$username . ' FROM ' . self::$tableName . ' WHERE ' . self::$username . '="' . $username . '"');
    return $result->num_rows > 0;
  }

  public function getHashedPassword(string $username) : string {
    $result = $this->connection->query('SELECT ' . self::$password . ' FROM ' . self::$tableName . ' WHERE ' . self::$username . '="' . $username . '"');
    
    if ($result->num_rows == 1) {
      $result = $result->fetch_assoc();
      return $result[self::$password];
    }

    return "";
  }

  public function addUserToDatabase(string $username, string $password) : bool {
    $sql = 'INSERT INTO ' . self::$tableName . ' (' . self::$username . ', ' . self::$password . ') 
    VALUES ("' . $username . '", "' . password_hash($password, PASSWORD_BCRYPT) . '")';
    return $this->connection->query($sql);
  }
}
?>
<?php
namespace model;

class Session {
  private static $message = "flash";
  private static $loggedIn = "loggedIn";
  private static $enteredUsername = "enteredUsername";

  public function __construct() {
    if (!isset($_SESSION[self::$message])) {
      $_SESSION[self::$message] = "";
      $_SESSION[self::$loggedIn] = false;
      $_SESSION[self::$enteredUsername] = "";
    }
  }

  public function getMessage() : string {
    return $_SESSION[self::$message];
  }

  public function setMessage(string $message) {
    $_SESSION[self::$message] = $message;
  }

  public function addToMessage(string $message) {
    $_SESSION[self::$message] .= $message;
  }

  public function isMessageEmpty() : bool {
    return $_SESSION[self::$message] == "";
  }

  public function isLoggedIn() : bool {
    return $_SESSION[self::$loggedIn];
  }

  public function setLoggedIn(bool $loggedIn) {
    $_SESSION[self::$loggedIn] = $loggedIn;
  }

  public function getEnteredUsername() : string {
    return $_SESSION[self::$enteredUsername];
  }

  public function setEnteredUsername(string $enteredUsername) {
    $_SESSION[self::$enteredUsername] = $enteredUsername;
  }
}
?>
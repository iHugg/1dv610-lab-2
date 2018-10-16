<?php
namespace view;

class Session {
  private static $message = "flash";
  private static $loggedIn = "loggedIn";
  private static $enteredUsername = "enteredUsername";
  private static $browserName = "browser";

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
    $_SESSION[self::$message] .= $message . "<br>";
  }

  public function isMessageEmpty() : bool {
    return $_SESSION[self::$message] == "";
  }

  public function isLoggedIn() : bool {
    return $_SESSION[self::$loggedIn];
  }

  public function login() {
    $_SESSION[self::$loggedIn] = true;
  }

  public function logout() {
    $_SESSION[self::$loggedIn] = false;
  }

  public function getEnteredUsername() : string {
    return $_SESSION[self::$enteredUsername];
  }

  public function setEnteredUsername(string $enteredUsername) {
    $_SESSION[self::$enteredUsername] = $enteredUsername;
  }

  public function getBrowserName() : string {
    return $_SESSION[self::$browserName];
  }

  public function setBrowserName(string $browserName) {
    $_SESSION[self::$browserName] = $browserName;
  }

  public function isBrowserSet() {
    return isset($_SESSION[self::$browserName]);
  }
}
?>
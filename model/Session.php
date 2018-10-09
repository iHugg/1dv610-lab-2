<?php
namespace model;

class Session {
  private static $message = "flash";

  public function __construct() {
    if (!isset($_SESSION[self::$message])) {
      $_SESSION[self::$message] = "";
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

  public function isMessageEmpty() {
    return $_SESSION[self::$message] == "";
  }
}
?>
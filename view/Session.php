<?php
namespace view;

class Session {
  private static $message = "flash";
  private static $loggedIn = "loggedIn";
  private static $enteredUsername = "enteredUsername";
  private static $browserName = "browser";
  private static $username = "username";
  private static $threadTitle = "threadTitle";
  private static $post = "post";

  public function __construct() {
    if (!isset($_SESSION[self::$message])) {
      $_SESSION[self::$message] = "";
      $_SESSION[self::$loggedIn] = false;
      $_SESSION[self::$enteredUsername] = "";
      $_SESSION[self::$username] = "";
      $_SESSION[self::$threadTitle] = "";
      $_SESSION[self::$post] = "";
      $this->setBrowserName();
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

  private function setBrowserName() {
    $_SESSION[self::$browserName] = $_SERVER["HTTP_USER_AGENT"];
  }

  public function isBrowserSet() {
    return isset($_SESSION[self::$browserName]);
  }

  public function getUsername() : string {
    return $_SESSION[self::$username];
  }

  public function setUsername(string $username) {
    $_SESSION[self::$username] = $username;
  }

  public function getThreadTitle() : string {
    return $_SESSION[self::$threadTitle];
  }

  public function setThreadTitle(string $title) {
    $_SESSION[self::$threadTitle] = $title;
  }

  public function getPost() : string {
    return $_SESSION[self::$post];
  }

  public function setPost(string $post) {
    $_SESSION[self::$post] = $post;
  }
}
?>
<?php
namespace view;

class FlashMessagePrinter {
  private $session;
  private $userLimits;

  public function __construct() {
    $this->session = new Session();
    $this->userLimits = new \model\UserLimitations();
  }

  public function loggedIn() {
    $this->session->setMessage("Welcome");
  }

  public function rememberLogin() {
    $this->session->setMessage("Welcome and you will be remembered");
  }

  public function loggedInWithCookies() {
    $this->session->setMessage("Welcome back with cookie");
  }

  public function usernameMissing() {
    $this->session->setMessage("Username is missing");
  }

  public function passwordMissing() {
    $this->session->setMessage("Password is missing");
  }

  public function wrongCredentials() {
    $this->session->setMessage("Wrong name or password");
  }

  public function logout() {
    $this->session->setMessage("Bye bye!");
  }

  public function userRegistered() {
    $this->session->setMessage("Registered new user.");
  }

  public function registerDatabaseError() {
    $this->session->setMessage("Something went wrong when registering the user.");
  }

  public function usernameTooShort() {
    $this->session->addToMessage("Username has too few characters, at least " . $this->userLimits->getUsernameMinLength() . " characters.");
  }

  public function passwordTooShort() {
    $this->session->addToMessage("Password has too few characters, at least " . $this->userLimits->getPasswordMinLength() . " characters.");
  }

  public function passwordsDontMatch() {
    $this->session->addToMessage("Passwords do not match.");
  }

  public function usernameAlreadyExists() {
    $this->session->addToMessage("User exists, pick another username.");
  }

  public function invalidCharacter() {
    $this->session->addToMessage("Username contains invalid characters.");
  }

  public function cookieError() {
    $this->session->setMessage("Wrong information in cookies");
  }

  public function emptyMessage() {
    $this->session->setMessage("");
  }
}
?>
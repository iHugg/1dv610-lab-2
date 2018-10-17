<?php
namespace view;

class SessionPrinter {
  private $session;
  private $loginView;
  private $registerView;

  public function __construct() {
    $this->session = new Session();
    $this->loginView = new LoginView();
    $this->registerView = new RegisterView();
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

  public function usernameTooShort(\model\User $user) {
    $this->session->addToMessage("Username has too few characters, at least " . $user->getUsernameMinLength() . " characters.");
  }

  public function passwordTooShort(\model\User $user) {
    $this->session->addToMessage("Password has too few characters, at least " . $user->getPasswordMinLength() . " characters.");
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

  public function setLoginEnteredUsername() {
    $enteredUsername = $this->loginView->getEnteredUsername();
    $this->session->setEnteredUsername($enteredUsername);
  }

  public function setRegisterEnteredUsername() {
    $enteredUsername = $this->registerView->getUsername();
    $username = $this->removeTagsAndInvalidCharacters($enteredUsername);
    $this->session->setEnteredUsername($username);
  }

  private function removeTagsAndInvalidCharacters(string $username) : string {
    $username = strip_tags($username);
    return preg_replace('/[^A-Za-z0-9\-]/', '', $username);
  }
}
?>
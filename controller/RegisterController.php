<?php
namespace controller;

class RegisterController {
  private $registerView;
  private $layoutView;
  private $session;
  private $database;
  private $userLimits;
  private $sessionPrinter;

  public function __construct(\view\RegisterView $registerView, \view\LayoutView $layoutView) {
    $this->registerView = $registerView;
    $this->layoutView = $layoutView;
    $this->session = new \view\Session();
    $this->sessionPrinter = new \view\SessionPrinter();
    $this->database = new \model\Database();
    $this->userLimits = new \model\UserLimitations();
  }

  public function handleRegister() {
    if (!$this->checkIfCredentialsAreWrong()) {
      $this->registerUser();
      $this->layoutView->redirectToLoginPage();
    } else {
      $this->layoutView->redirectToRegisterPage();
    }
  }

  private function registerUser() {
    $username = $this->registerView->getUsername();
    $password = $this->registerView->getPassword();

    if ($this->database->addUserToDatabase($username, $password)) {
      $this->sessionPrinter->userRegistered();
    } else {
      $this->sessionPrinter->registerDatabaseError();
    }
  }

  //  Really ugly solution, can't think of another way of checking all issues after first issue has been found.
  private function checkIfCredentialsAreWrong() : bool {
    $username = $this->registerView->getUsername();
    $password = $this->registerView->getPassword();
    $repeatPassword = $this->registerView->getRepeatPassword();
    $this->sessionPrinter->setRegisterEnteredUsername();
    $errorFound = false;

    if (!$this->isUsernameLengthOkay($username)) {
      $errorFound = true;
    }

    if (!$this->isPasswordLengthOkay($password)) {
      $errorFound = true;
    }

    if (!$this->checkIfPasswordsMatch($password, $repeatPassword)) {
      $errorFound = true;
    }

    if ($this->checkIfUsernameExists($username)) {
      $errorFound = true;
    }

    if ($this->checkUsernameInvalidCharacters($username)) {
      $errorFound = true;
    }

    return $errorFound;
  }

  private function isUsernameLengthOkay(string $username) : bool {
    if (strlen($username) < $this->userLimits->getUsernameMinLength()) {
      $this->sessionPrinter->usernameTooShort();
      return false;
    }

    return true;
  }

  private function isPasswordLengthOkay(string $password) : bool {
    if (strlen($password) < $this->userLimits->getPasswordMinLength()) {
      $this->sessionPrinter->passwordTooShort();
      return false;
    }

    return true;
  }

  private function checkIfPasswordsMatch(string $password, string $repeatPassword) : bool {
    if ($password != $repeatPassword) {
      $this->sessionPrinter->passwordsDontMatch();
      return false;
    }

    return true;
  }

  private function checkIfUsernameExists(string $username) : bool {
    if ($this->database->userExists($username)) {
      $this->sessionPrinter->usernameAlreadyExists();
      return true;
    }

    return false;
  }

  private function checkUsernameInvalidCharacters(string $username) : bool {
    if (preg_match('(<|>)', $username) === 1) {
      $this->sessionPrinter->invalidCharacter();
      return true;
    }
    return false;
  }
}
?>
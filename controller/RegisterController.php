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
    $user = new \model\User($username, $password);

    if ($user->usernameIsTooShort()) {
      $this->sessionPrinter->usernameTooShort();
      $errorFound = true;
    }

    if ($user->passwordIsTooShort()) {
      $this->sessionPrinter->passwordTooShort();
      $errorFound = true;
    }

    if (!$user->passwordsMatch($repeatPassword)) {
      $this->sessionPrinter->passwordsDontMatch();
      $errorFound = true;
    }

    if ($this->database->userExists($user->getUsername())) {
      $this->sessionPrinter->usernameAlreadyExists();
      $errorFound = true;
    }

    if ($user->usernameHasInvalidChar()) {
      $this->sessionPrinter->invalidCharacter();
      $errorFound = true;
    }

    return $errorFound;
  }
}
?>
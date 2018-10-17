<?php
namespace controller;

class RegisterController extends BaseController {

  public function __construct(\mysqli $connection) {
    parent::__construct($connection);
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

    if ($this->userSQL->addUserToDatabase($username, $password)) {
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
      $this->sessionPrinter->usernameTooShort($user);
      $errorFound = true;
    }

    if ($user->passwordIsTooShort()) {
      $this->sessionPrinter->passwordTooShort($user);
      $errorFound = true;
    }

    if (!$user->passwordsMatch($repeatPassword)) {
      $this->sessionPrinter->passwordsDontMatch();
      $errorFound = true;
    }

    if ($this->userSQL->userExists($user->getUsername())) {
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
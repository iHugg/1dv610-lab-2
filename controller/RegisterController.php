<?php
namespace controller;

/**
 * Handles the registering of users.
 */
class RegisterController extends BaseController {

  public function __construct(\mysqli $connection) {
    parent::__construct($connection);
  }

  public function handleRegister() {
    $user = $this->getUser();

    if (!$this->checkIfCredentialsAreWrong($user)) {
      $this->registerUser($user);
      $this->layoutView->redirectToLoginPage();
    } else {
      $this->layoutView->redirectToRegisterPage();
    }
  }

  private function getUser() : \model\User {
    $username = $this->registerView->getUsername();
    $password = $this->registerView->getPassword();
    return new \model\User($username, $password);
  }

  private function registerUser(\model\User $user) {
    if ($this->userSQL->addUserToDatabase($user->getUsername(), $user->getPassword())) {
      $this->sessionPrinter->userRegistered();
    } else {
      $this->sessionPrinter->registerDatabaseError();
    }
  }

  //  Really ugly solution, can't think of another way of checking all issues after first issue has been found.
  private function checkIfCredentialsAreWrong(\model\User $user) : bool {
    $repeatPassword = $this->registerView->getRepeatPassword();
    $this->sessionPrinter->setRegisterEnteredUsername();
    $errorFound = false;

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
<?php
namespace controller;

class RegisterController {
  private $registerView;
  private $layoutView;
  private $session;
  private $database;
  private $userLimits;
  private $flashPrinter;

  public function __construct(\view\RegisterView $registerView, \view\LayoutView $layoutView) {
    $this->registerView = $registerView;
    $this->layoutView = $layoutView;
    $this->session = new \view\Session();
    $this->flashPrinter = new \view\FlashMessagePrinter();
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
      $this->flashPrinter->userRegistered();
    } else {
      $this->flashPrinter->registerDatabaseError();
    }
  }

  //  Really ugly solution, can't think of another way of checking all issues after first issue has been found.
  private function checkIfCredentialsAreWrong() : bool {
    $username = $this->registerView->getUsername();
    $password = $this->registerView->getPassword();
    $repeatPassword = $this->registerView->getRepeatPassword();
    $this->session->setEnteredUsername($username);
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
      $this->flashPrinter->usernameTooShort();
      return false;
    }

    return true;
  }

  private function isPasswordLengthOkay(string $password) : bool {
    if (strlen($password) < $this->userLimits->getPasswordMinLength()) {
      $this->flashPrinter->passwordTooShort();
      return false;
    }

    return true;
  }

  private function checkIfPasswordsMatch(string $password, string $repeatPassword) : bool {
    if ($password != $repeatPassword) {
      $this->flashPrinter->passwordsDontMatch();
      return false;
    }

    return true;
  }

  private function checkIfUsernameExists(string $username) : bool {
    if ($this->database->userExists($username)) {
      $this->flashPrinter->usernameAlreadyExists();
      return true;
    }

    return false;
  }

  private function checkUsernameInvalidCharacters(string $username) : bool {
    foreach (str_split($username) as $char) {
      if ($char == '>' || $char == '<') {
        $this->removeTagsFromUsername($username);
        return true;
      }
    }

    return false;
  }

  private function removeTagsFromUsername(string $username) {
    $username = strip_tags($username);
    $this->session->setEnteredUsername(preg_replace('/[^A-Za-z0-9\-]/', '', $username));
    $this->flashPrinter->invalidCharacter();
  }
}
?>
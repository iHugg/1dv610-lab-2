<?php
namespace controller;

class RegisterController {
  private $registerView;
  private $layoutView;
  private $session;
  private $database;
  private $usernameMinCharacters;
  private $passwordMinCharacters;

  public function __construct(\view\RegisterView $registerView, \view\LayoutView $layoutView) {
    $this->registerView = $registerView;
    $this->layoutView = $layoutView;
    $this->session = new \model\Session();
    $this->database = new \model\Database();
    $this->usernameMinCharacters = 3;
    $this->passwordMinCharacters = 6;
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
      $this->session->setMessage("Registered new user.");
    } else {
      $this->session->setMessage("Something went wrong when registering the user.");
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
    if (strlen($username) < $this->usernameMinCharacters) {
      $this->session->addToMessage("Username has too few characters, at least " . $this->usernameMinCharacters . " characters.");
      return false;
    }

    return true;
  }

  private function isPasswordLengthOkay(string $password) : bool {
    if (strlen($password) < $this->passwordMinCharacters) {
      $this->session->addToMessage("Password has too few characters, at least " . $this->passwordMinCharacters . " characters.");
      return false;
    }

    return true;
  }

  private function checkIfPasswordsMatch(string $password, string $repeatPassword) : bool {
    if ($password != $repeatPassword) {
      $this->session->addToMessage("Passwords do not match.");
      return false;
    }

    return true;
  }

  private function checkIfUsernameExists(string $username) : bool {
    if ($this->database->userExists($username)) {
      $this->session->addToMessage("User exists, pick another username.");
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
    $this->session->addToMessage("Username contains invalid characters.");
  }
}
?>
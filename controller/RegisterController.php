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
    if (!$this->checkCredentials()) {

    }

    $this->layoutView->redirectToRegisterPage();
  }

  private function checkCredentials() : bool {
    $username = $this->registerView->getUsername();
    $password = $this->registerView->getPassword();
    $repeatPassword = $this->registerView->getRepeatPassword();
    $this->session->setEnteredUsername($username);

    if ($this->isUsernameLengthOkay($username) &&
    $this->isPasswordLengthOkay($password) &&
    $this->checkIfPasswordsMatch($password, $repeatPassword) &&
    !$this->checkIfUsernameExists($username) &&
    !$this->checkUsernameInvalidCharacters($username)) {
      return true;
    }

    return false;
  }

  private function isUsernameLengthOkay(string $username) : bool {
    if (strlen($username) < $this->usernameMinCharacters) {
      $this->session->setMessage("Username has too few characters, at least " . $this->usernameMinCharacters . " characters.<br>");
      return false;
    }

    return true;
  }

  private function isPasswordLengthOkay(string $password) : bool {
    if (strlen($password) < $this->passwordMinCharacters) {
      $this->session->addToMessage("Password has too few characters, at least " . $this->passwordMinCharacters . " characters.<br>");
      return false;
    }

    return true;
  }

  private function checkIfPasswordsMatch(string $password, string $repeatPassword) : bool {
    if ($password != $repeatPassword) {
      $this->session->addToMessage("Passwords do not match.<br>");
      return false;
    }

    return true;
  }

  private function checkIfUsernameExists(string $username) : bool {
    if ($this->database->userExists($username)) {
      $this->session->addToMessage("User exists, pick another username.<br>");
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
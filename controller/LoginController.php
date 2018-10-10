<?php
  namespace controller;

  class LoginController {
    private $loginView;
    private $layoutView;
    private $session;
    private $database;

    public function __construct(\view\LoginView $loginView, \view\LayoutView $layoutView) {
      $this->loginView = $loginView;
      $this->layoutView = $layoutView;
      $this->session = new \model\Session();
      $this->database = new \model\Database();
    }

    public function handleLogin() {
      $user = $this->getUserCredentials();

      if (!$this->areCredentialsEmpty($user)) {
        $this->checkLoginCredentials($user);
      }
      $this->layoutView->redirectToLoginPage();
    }

    private function areCredentialsEmpty(\model\User $user) : bool {
      if ($user->isUsernameEmpty()) {
        $this->session->setMessage("Username is missing");
        return true;
      } else if ($user->isPasswordEmpty()) {
        $this->session->setMessage("Password is missing");
        $this->session->setEnteredUsername($user->getUsername());
        return true;
      }

      return false;
    }

    private function checkLoginCredentials(\model\User $user) {
      $hashedPassword = $this->database->getHashedPassword($user->getUsername());

      if ($user->passwordsMatch($hashedPassword)) {
        $loggedIn = true;
        $this->session->setMessage("Welcome");
        $this->session->setLoggedIn($loggedIn);
      } else {
        $this->session->setMessage("Wrong name or password");
        $this->session->setEnteredUsername($user->getUsername());
      }
    }

    private function getUserCredentials() : \model\User {
      $username = $this->loginView->getEnteredUsername();
      $password = $this->loginView->getEnteredPassword();
      return new \model\User($username, $password);
    }
  }
?>
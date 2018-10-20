<?php
  namespace controller;

  /**
   * Controller which handles everything related to logging in.
   */
  class LoginController extends BaseController {

    public function __construct(\mysqli $connection) {
      parent::__construct($connection);
    }

    public function handleLoginByCookies() {
      $user = $this->getUser();
      $hashedPassword = $this->userSQL->getHashedPassword($user->getUsername());

      if (password_verify($hashedPassword, $user->getPassword())) {
        $this->sessionPrinter->loggedInWithCookies();
        $this->session->login();
        $this->session->setUsername($user->getUsername());
      }
    }

    private function getUser() : \model\User {
      $username = $this->loginView->getCookieName();
      $password = $this->loginView->getCookiePassword();
      return new \model\User($username, $password);
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
        $this->sessionPrinter->usernameMissing();
        return true;
      } else if ($user->isPasswordEmpty()) {
        $this->sessionPrinter->passwordMissing();
        $this->sessionPrinter->setLoginEnteredUsername();
        return true;
      }

      return false;
    }

    private function checkLoginCredentials(\model\User $user) {
      $hashedPassword = $this->userSQL->getHashedPassword($user->getUsername());

      if ($user->hashedPasswordMatch($hashedPassword)) {
        $this->loginUser($user);
      } else {
        $this->sessionPrinter->wrongCredentials();
        $this->sessionPrinter->setLoginEnteredUsername();
      }
    }

    private function loginUser(\model\User $user) {
      $this->sessionPrinter->loggedIn();
      $this->sessionPrinter->setLoggedInUsername($user);
      $this->session->login();
      $this->handleStayLoggedIn($user);
    }

    private function getUserCredentials() : \model\User {
      $username = $this->loginView->getEnteredUsername();
      $password = $this->loginView->getEnteredPassword();
      return new \model\User($username, $password);
    }

    private function handleStayLoggedIn(\model\User $user) {
      if ($this->loginView->wantsToStayLoggedIn()) {
        $username = $user->getUsername();
        $hashedPassword = $this->userSQL->getHashedPassword($username);
        $hashedPassword = password_hash($hashedPassword, PASSWORD_BCRYPT);
        $this->loginView->setLoginCookies($username, $hashedPassword);
        $this->saveCookieInformation($hashedPassword);
        $this->sessionPrinter->rememberLogin();
      }
    }

    private function saveCookieInformation(string $hashedPassword) {
      $this->browserSQL->saveBrowser($this->layoutView->getBrowser(), $hashedPassword);
    }
  }
?>
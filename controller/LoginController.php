<?php
  namespace controller;

  class LoginController {
    private $loginView;
    private $layoutView;
    private $session;
    private $database;
    private $browserDatabase;

    public function __construct(\view\LoginView $loginView, \view\LayoutView $layoutView) {
      $this->loginView = $loginView;
      $this->layoutView = $layoutView;
      $this->session = new \view\Session();
      $this->sessionPrinter = new \view\SessionPrinter();
      $this->database = new \model\Database();
      $this->browserDatabase = new \model\BrowserDatabase();
    }

    public function handleLoginByCookies() {
      $cookieUser = $this->loginView->getCookieUser();
      $hashedPassword = $this->database->getHashedPassword($cookieUser->getUsername());
      if (password_verify($hashedPassword, $cookieUser->getPassword())) {
        $this->sessionPrinter->loggedInWithCookies();
        $this->session->login();
      }
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
      $hashedPassword = $this->database->getHashedPassword($user->getUsername());

      if ($user->HashedPasswordMatch($hashedPassword)) {
        $this->sessionPrinter->loggedIn();
        $this->session->login();
        $this->handleStayLoggedIn($user);
      } else {
        $this->sessionPrinter->wrongCredentials();
        $this->sessionPrinter->setLoginEnteredUsername();
      }
    }

    private function getUserCredentials() : \model\User {
      $username = $this->loginView->getEnteredUsername();
      $password = $this->loginView->getEnteredPassword();
      return new \model\User($username, $password);
    }

    private function handleStayLoggedIn(\model\User $user) {
      if ($this->loginView->wantsToStayLoggedIn()) {
        $username = $user->getUsername();
        $hashedPassword = $this->database->getHashedPassword($username);
        $hashedPassword = password_hash($hashedPassword, PASSWORD_BCRYPT);
        $this->loginView->setLoginCookies($username, $hashedPassword);
        $this->saveCookieInformation($hashedPassword);
        $this->sessionPrinter->rememberLogin();
      }
    }

    private function saveCookieInformation(string $hashedPassword) {
      $this->browserDatabase->saveBrowser($this->layoutView->getBrowser(), $hashedPassword);
    }
  }
?>
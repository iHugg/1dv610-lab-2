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
      $this->session = new \model\Session();
      $this->database = new \model\Database();
      $this->browserDatabase = new \model\BrowserDatabase();
    }

    public function handleLoginByCookies() {
      $cookieUser = $this->loginView->getCookieUser();
      $hashedPassword = $this->database->getHashedPassword($cookieUser->getUsername());
      if (password_verify($hashedPassword, $cookieUser->getPassword())) {
        $loggedIn = true;
        $this->session->setMessage("Welcome back with cookie");
        $this->session->setLoggedIn($loggedIn);
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
        $this->handleStayLoggedIn($user);
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

    private function handleStayLoggedIn(\model\User $user) {
      if ($this->loginView->wantsToStayLoggedIn()) {
        $username = $user->getUsername();
        $hashedPassword = $this->database->getHashedPassword($username);
        $hashedPassword = password_hash($hashedPassword, PASSWORD_BCRYPT);
        $this->loginView->setLoginCookies($username, $hashedPassword);
        $this->saveCookieInformation($hashedPassword);
        $this->session->setMessage("Welcome and you will be remembered");
      }
    }

    private function saveCookieInformation(string $hashedPassword) {
      $this->browserDatabase->saveBrowser($this->layoutView->getBrowser(), $hashedPassword);
    }
  }
?>
<?php
namespace controller;

class MasterController {
  private $loginView;
  private $registerView;
  private $layoutView;
  private $sessionPrinter;
  private $session;
  private $loginController;
  private $logoutController;
  private $registerController;
  private $tamperingController;
  private $tamperingFound;
  private $isLoggedIn;

  public function __construct() {
    $this->loginView = new \view\LoginView();
    $this->registerView = new \view\RegisterView();
    $this->layoutView = new \view\LayoutView($this->loginView, $this->registerView);
    $this->sessionPrinter = new \view\SessionPrinter();
    $this->session = new \view\Session();
    $this->loginController = new LoginController($this->loginView, $this->layoutView);
    $this->logoutController = new LogoutController($this->loginView, $this->layoutView);
    $this->registerController = new RegisterController($this->registerView, $this->layoutView);
    $this->tamperingController = new TamperingController($this->layoutView, $this->loginView);
    $this->tamperingFound = false;
    $this->isLoggedIn = $this->session->isLoggedIn();
  }

  public function start() {
    $this->handleCookieTampering();
    $this->handleSessionTheft();
    $this->handleAction();

    $this->layoutView->render($this->isLoggedIn, $this->tamperingFound);
    $this->handleFlashMessage();
  }

  private function handleCookieTampering() {
    if ($this->loginView->loginCookiesExist() && $this->tamperingController->hasCookieBeenTamperedWith()) {
      $this->sessionPrinter->cookieError();
      $this->tamperingFound = true;
      $this->session->logout();
      $this->loginView->removeLoginCookies();
    }
  }

  private function handleSessionTheft() {
    if ($this->tamperingController->hasSessionBeenStolen()) {
      $this->tamperingFound = true;
      $this->isLoggedIn = false;
    }
  }

  private function handleAction() {
    if ($this->loginView->wantsToLogin() && !$this->session->isLoggedIn()) {
      $this->loginController->handleLogin();
    } else if ($this->loginView->wantsToLogout() && $this->session->isLoggedIn()) {
      $this->logoutController->handleLogout();
    } else if ($this->registerView->wantsToRegister()) {
      $this->registerController->handleRegister();
    } else if ($this->loginView->loginCookiesExist() && !$this->session->isLoggedIn()) {
      $this->loginController->handleLoginByCookies();
      $this->isLoggedIn = $session->isLoggedIn();
    }
  }

  private function handleFlashMessage() {
    if (!$this->loginView->wantsToLogin() && !$this->loginView->wantsToLogout() && !$this->registerView->wantsToRegister()) {
      $this->sessionPrinter->emptyMessage();
    }
  }
}
?>
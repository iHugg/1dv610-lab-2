<?php
namespace controller;

class LogoutController {
  private $loginView;
  private $layoutView;
  private $session;
  private $sessionPrinter;

  public function __construct(\view\LoginView $loginView, \view\LayoutView $layoutView) {
    $this->loginView = $loginView;
    $this->layoutView = $layoutView;
    $this->session = new \view\Session();
    $this->sessionPrinter = new \view\SessionPrinter();
  }

  public function handleLogout() {
    $this->session->logout();
    $this->sessionPrinter->logout();
    $this->loginView->removeLoginCookies();
    $this->layoutView->redirectToLoginPage();
  }
}
?>
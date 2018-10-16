<?php
namespace controller;

class LogoutController {
  private $loginView;
  private $layoutView;
  private $session;
  private $flashPrinter;

  public function __construct(\view\LoginView $loginView, \view\LayoutView $layoutView) {
    $this->loginView = $loginView;
    $this->layoutView = $layoutView;
    $this->session = new \view\Session();
    $this->flashPrinter = new \view\FlashMessagePrinter();
  }

  public function handleLogout() {
    $loggedIn = false;
    $this->session->setLoggedIn($loggedIn);
    $this->flashPrinter->logout();
    $this->loginView->removeLoginCookies();
    $this->layoutView->redirectToLoginPage();
  }
}
?>
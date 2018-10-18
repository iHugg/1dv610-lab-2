<?php
namespace controller;

class LogoutController extends BaseController {

  public function __construct(\mysqli $connection) {
    parent::__construct($connection);
  }

  public function handleLogout() {
    $this->session->logout();
    $this->session->setUsername("");
    $this->sessionPrinter->logout();
    $this->loginView->removeLoginCookies();
    $this->layoutView->redirectToLoginPage();
  }
}
?>
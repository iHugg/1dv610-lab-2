<?php
namespace controller;

class LogoutController extends BaseController {

  public function __construct() {
    parent::__construct();
  }

  public function handleLogout() {
    $this->session->logout();
    $this->sessionPrinter->logout();
    $this->loginView->removeLoginCookies();
    $this->layoutView->redirectToLoginPage();
  }
}
?>
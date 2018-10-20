<?php
namespace controller;

/**
 * Kicks in when a user wants to logout.
 */
class LogoutController extends BaseController {

  public function __construct(\mysqli $connection) {
    parent::__construct($connection);
  }

  public function handleLogout() {
    $this->session->logout();
    $this->sessionPrinter->emptyUsername();
    $this->sessionPrinter->logout();
    $this->loginView->removeLoginCookies();
    $this->layoutView->redirectToLoginPage();
  }
}
?>
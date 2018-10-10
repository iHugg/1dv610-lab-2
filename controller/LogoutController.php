<?php
namespace controller;

class LogoutController {
  private $loginView;
  private $layoutView;
  private $session;

  public function __construct(\view\LoginView $loginView, \view\LayoutView $layoutView) {
    $this->loginView = $loginView;
    $this->layoutView = $layoutView;
    $this->session = new \model\Session();
  }

  public function handleLogout() {
    $loggedIn = false;
    $this->session->setLoggedIn($loggedIn);
    $this->session->setMessage("Bye bye!");
    $this->layoutView->redirectToLoginPage();
  }
}
?>
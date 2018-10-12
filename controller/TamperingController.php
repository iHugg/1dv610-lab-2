<?php
namespace controller;

class TamperingController {
  private $layoutView;
  private $loginView;
  private $browserDatabase;
  private $session;

  public function __construct(\view\LayoutView $layoutView, \view\LoginView $loginView) {
    $this->layoutView = $layoutView;
    $this->loginView = $loginView;
    $this->browserDatabase = new \model\BrowserDatabase();
    $this->session = new \model\Session();
  }

  public function hasCookieBeenTamperedWith() : bool {
    $currentBrowser = $this->layoutView->getBrowser();
    $cookieUser = $this->loginView->getCookieUser();

    $passwordCookie = $this->browserDatabase->getCookiePassword($currentBrowser);

    return $cookieUser->getPassword() != $passwordCookie;
  }

  public function hasSessionBeenStolen() : bool {
    $currentBrowser = $this->layoutView->getBrowser();
    $browserWhenLoggedIn = $this->session->getBrowserName();
    return $currentBrowser != $browserWhenLoggedIn;
  }
}
?>
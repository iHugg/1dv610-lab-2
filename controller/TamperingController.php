<?php
namespace controller;

class TamperingController extends BaseController {

  public function __construct() {
    parent::__construct();
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
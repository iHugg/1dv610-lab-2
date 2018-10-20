<?php
namespace controller;

/**
 * Checks if any tampering has been made.
 */
class TamperingController extends BaseController {

  public function __construct(\mysqli $connection) {
    parent::__construct($connection);
  }

  public function hasCookieBeenTamperedWith() : bool {
    $currentBrowser = $this->layoutView->getBrowser();
    $cookieUser = $this->getUser();

    $passwordCookie = $this->browserSQL->getCookiePassword($currentBrowser);

    return $cookieUser->getPassword() != $passwordCookie;
  }

  private function getUser() : \model\User {
    $username = $this->loginView->getCookieName();
    $password = $this->loginView->getCookiePassword();
    return new \model\User($username, $password);
  }

  public function hasSessionBeenStolen() : bool {
    $currentBrowser = $this->layoutView->getBrowser();
    $browserWhenLoggedIn = $this->session->getBrowserName();
    return $currentBrowser != $browserWhenLoggedIn;
  }
}
?>
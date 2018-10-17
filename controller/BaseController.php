<?php
namespace controller;

class BaseController {
  protected $loginView;
  protected $registerView;
  protected $layoutView;
  protected $threadView;
  protected $sessionPrinter;
  protected $session;
  protected $browserSQL;
  protected $userSQL;
  protected $threadSQL;

  public function __construct(\mysqli $connection) {
    $this->loginView = new \view\LoginView();
    $this->registerView = new \view\RegisterView();
    $this->layoutView = new \view\LayoutView($this->loginView, $this->registerView, $connection);
    $this->threadView = new \view\ThreadView($connection);
    $this->sessionPrinter = new \view\SessionPrinter();
    $this->session = new \view\Session();
    $this->browserSQL = new \model\BrowserSQL($connection);
    $this->userSQL = new \model\UserSQL($connection);
    $this->threadSQL = new \model\ThreadSQL($connection);
  }
}
?>
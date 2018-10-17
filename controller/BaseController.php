<?php
namespace controller;

class BaseController {
  protected $loginView;
  protected $registerView;
  protected $layoutView;
  protected $threadView;
  protected $sessionPrinter;
  protected $session;
  protected $database;
  protected $browserDatabase;

  public function __construct() {
    $this->loginView = new \view\LoginView();
    $this->registerView = new \view\RegisterView();
    $this->layoutView = new \view\LayoutView($this->loginView, $this->registerView);
    $this->threadView = new \view\ThreadView();
    $this->sessionPrinter = new \view\SessionPrinter();
    $this->session = new \view\Session();
    $this->database = new \model\Database();
    $this->browserDatabase = new \model\BrowserDatabase();
  }
}
?>
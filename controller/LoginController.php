<?php
  namespace controller;

  class LoginController {
    private $loginView;
    private $layoutView;
    private $dateTimeView;
    private $session;

    public function __construct(\view\LoginView $loginView, \view\LayoutView $layoutView, \view\DateTimeView $dateTimeView) {
      $this->loginView = $loginView;
      $this->layoutView = $layoutView;
      $this->dateTimeView = $dateTimeView;
      $this->session = new \model\Session();
    }

    public function checkEmptyLoginFields() {
      if ($this->loginView->isUsernameEmpty()) {
        $this->session->setMessage("Username is missing");
      } else if ($this->loginView->isPasswordEmpty()) {
        $this->session->setMessage("Password is missing");
      }
    }

    public function checkLoginCredentials() {
      //TODO
    }
  }
?>
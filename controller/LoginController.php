<?php
  namespace controller;

  class LoginController {
    private $loginView;
    private $layoutView;
    private $dateTimeView;
    private $session;
    private $database;

    public function __construct(\view\LoginView $loginView, \view\LayoutView $layoutView, \view\DateTimeView $dateTimeView) {
      $this->loginView = $loginView;
      $this->layoutView = $layoutView;
      $this->dateTimeView = $dateTimeView;
      $this->session = new \model\Session();
      $this->database = new \model\Database();
    }

    public function checkEmptyLoginFields(\model\User $user) {
      if ($user->isUsernameEmpty()) {
        $this->session->setMessage("Username is missing");
      } else if ($user->isPasswordEmpty()) {
        $this->session->setMessage("Password is missing");
      }
    }

    public function checkLoginCredentials(\model\User $user) {
      if ($this->database->userExists($user->getUsername())) {
        $this->session->setMessage("User exists!");
      } else {
        $this->session->setMessage("User doesn't exist :(");
      }
      /*if ($this->database->doesUserExist($user->getUsername())) {

      }*/
    }
  }
?>
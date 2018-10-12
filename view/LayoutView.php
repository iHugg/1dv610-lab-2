<?php
namespace view;

class LayoutView {
  private $loginView;
  private $dateTimeView;
  private $registerView;
  private $query;

  public function __construct(LoginView $loginView, RegisterView $registerView) {
    $this->loginView = $loginView;
    $this->dateTimeView = new DateTimeView();
    $this->registerView = $registerView;
    $this->query = "register=1";
  }
  
  public function render(bool $isLoggedIn, bool $tamperingFound) {
    $mainBody = $this->getContent($isLoggedIn);
    if ($tamperingFound) {
      $isLoggedIn = false;
      $mainBody = $this->loginView->response($isLoggedIn);
    }

    echo '<!DOCTYPE html>
      <html>
        <head>
          <meta charset="utf-8">
          <title>Login Example</title>
        </head>
        <body>
          <h1>Assignment 2</h1>
          ' . $this->renderRegisterLink() . '
          ' . $this->renderIsLoggedIn($isLoggedIn) . '
          
          <div class="container">
              ' . $mainBody . '
              ' . $this->dateTimeView->show() . '
          </div>
         </body>
      </html>
    ';
  }
  
  private function renderIsLoggedIn(bool $isLoggedIn) : string {
    if ($isLoggedIn) {
      return '<h2>Logged in</h2>';
    }
    else {
      return '<h2>Not logged in</h2>';
    }
  }

  private function getContent(bool $isLoggedIn) : string {
    if ($_SERVER["QUERY_STRING"] == $this->query) {
      return $this->registerView->generateRegisterFormHTML();
     } else {
      return $this->loginView->response($isLoggedIn);
     }
  }

  private function renderRegisterLink() {
    $location = "";
    $query = "";
    $aTagMessage = "Back to login";

    if (strlen($_SERVER["QUERY_STRING"]) == 0) {
      $query = $this->query;
      $aTagMessage = "Register a new user";
    }

    if ($_SERVER["HTTP_HOST"] == "localhost") {
      $location = "/1dv610-lab-2";
    }

    return '<a href="' . $location . '/index.php?' . $query . '" id="register">' . $aTagMessage . '</a>';
  }

  public function redirectToLoginPage() {
    header("Location: http://" . $_SERVER["HTTP_HOST"] . $_SERVER["PHP_SELF"]);
  }

  public function redirectToRegisterPage() {
    header("Location: http://" . $_SERVER["HTTP_HOST"] . $_SERVER["PHP_SELF"] . "?" . $this->query);
  }

  public function getBrowser() {
    return $_SERVER["HTTP_USER_AGENT"];
  }
}

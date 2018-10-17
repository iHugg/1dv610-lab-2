<?php
namespace view;

class LayoutView {
  private $loginView;
  private $dateTimeView;
  private $registerView;
  private $threadView;
  private $registerQuery;
  private $threadQuery;

  public function __construct(LoginView $loginView, RegisterView $registerView) {
    $this->loginView = $loginView;
    $this->dateTimeView = new DateTimeView();
    $this->registerView = $registerView;
    $this->threadView = new ThreadView();
    $this->registerQuery = "register=1";
    $this->threadQuery = "thread=1";
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
          ' . $this->renderRegisterLink() . '<br>
          ' . $this->renderThreadLink() . '
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
    $queryString = $this->getQueryString();
    if ($queryString == $this->registerQuery) {
      return $this->registerView->generateRegisterFormHTML();
     } else if ($queryString == $this->threadQuery) {
      return $this->threadView->generateThreadHTML();
     } else if ($queryString == $this->threadView->getCreateThreadQuery()) {
      return $this->threadView->generateCreateThreadHTML();
     } else {
      return $this->loginView->response($isLoggedIn);
     }
  }

  private function getQueryString() {
    return $_SERVER["QUERY_STRING"];
  }

  private function renderRegisterLink() {
    $location = $this->getLocation();
    $query = "";
    $aTagMessage = "Back to login";

    if (strlen($_SERVER["QUERY_STRING"]) == 0) {
      $query = $this->registerQuery;
      $aTagMessage = "Register a new user";
    }

    return '<a href="' . $location . '/index.php?' . $query . '" id="register">' . $aTagMessage . '</a>';
  }

  private function renderThreadLink() {
    $location = $this->getLocation();

    return '<a href="' . $location . '/index.php?' . $this->threadQuery . '" id="thread"> Go to threads</a>';
  }

  private function getLocation() {
    if ($_SERVER["HTTP_HOST"] == "localhost") {
      return "/1dv610-lab-2";
    }

    return "";
  }

  public function redirectToLoginPage() {
    header("Location: http://" . $_SERVER["HTTP_HOST"] . $_SERVER["PHP_SELF"]);
  }

  public function redirectToRegisterPage() {
    header("Location: http://" . $_SERVER["HTTP_HOST"] . $_SERVER["PHP_SELF"] . "?" . $this->query);
  }

  public function redirectToThreadPage() {
    header("Location: http://" . $_SERVER["HTTP_HOST"] . $_SERVER["PHP_SELF"] . "?" . $this->threadQuery);
  }

  public function getBrowser() {
    return $_SERVER["HTTP_USER_AGENT"];
  }
}

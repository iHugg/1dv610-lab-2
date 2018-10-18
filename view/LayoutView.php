<?php
namespace view;

class LayoutView {
  private $loginView;
  private $dateTimeView;
  private $registerView;
  private $threadView;
  private $postView;
  private $registerQuery;
  private $threadQuery;

  public function __construct(LoginView $loginView, RegisterView $registerView, \mysqli $connection) {
    $this->loginView = $loginView;
    $this->dateTimeView = new DateTimeView();
    $this->registerView = $registerView;
    $this->threadView = new ThreadView($connection);
    $this->postView = new PostView();
    $this->connection = $connection;
    $this->registerQuery = "register";
    $this->threadQuery = "thread";
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
    parse_str($queryString, $result);
    if (isset($result[$this->registerQuery])) {
      return $this->registerView->generateRegisterFormHTML();
     } else if (isset($result[$this->threadQuery])) {
      return $this->threadView->generateThreadHTML();
     } else if (isset($result[$this->threadView->getCreateThreadQuery()])) {
      return $this->threadView->generateCreateThreadHTML();
     } else if (isset($result[$this->threadView->getCreatedThreadQuery()])) {
      return $this->threadView->generateUserCreatedThreadHTML($result["title"]);
     } else if (isset($result[$this->threadView->getCreatePostQuery()])) {
      return $this->postView->generatePostHTML($result["title"]);
     }
      else {
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
      $query = $this->registerQuery . '=1';
      $aTagMessage = "Register a new user";
    }

    return '<a href="' . $location . '/index.php?' . $query . '" id="' . $query . '">' . $aTagMessage . '</a>';
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
    header("Location: http://" . $_SERVER["HTTP_HOST"] . $_SERVER["PHP_SELF"] . "?" . $this->registerQuery . "=1");
  }

  public function redirectToThreadPage() {
    header("Location: http://" . $_SERVER["HTTP_HOST"] . $_SERVER["PHP_SELF"] . "?" . $this->threadQuery . '=1');
  }

  public function redirectToCreatedThreadPage(string $title) {
    header("Location: http://" . $_SERVER["HTTP_HOST"] . $_SERVER["PHP_SELF"] . "?" . $this->threadView->getCreatedThreadQuery() . "=1&title=" . $title);
  }

  public function getBrowser() {
    return $_SERVER["HTTP_USER_AGENT"];
  }
}

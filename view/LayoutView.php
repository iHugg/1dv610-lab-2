<?php
namespace view;

class LayoutView extends BaseView {
  private $loginView;
  private $dateTimeView;
  private $registerView;
  private $threadView;
  private $postView;
  private $registerQuery;
  private $threadQuery;

  public function __construct(\mysqli $connection) {
    parent::__construct($connection);
    $this->loginView = new LoginView();
    $this->dateTimeView = new DateTimeView();
    $this->registerView = new RegisterView();
    $this->threadView = new ThreadView($connection);
    $this->postView = new PostView($connection);
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
    if (isset($_GET[$this->registerQuery])) {
      return $this->registerView->generateRegisterFormHTML();
    } else if (isset($_GET[$this->threadQuery])) {
      return $this->threadView->generateThreadHTML();
    } else if (isset($_GET[self::$createThreadQuery]) && $isLoggedIn) {
      return $this->threadView->generateCreateThreadHTML();
    } else if (isset($_GET[self::$userCreatedThreadQuery]) &&
      isset($_GET[self::$idQuery])) {
      return $this->threadView->generateUserCreatedThreadHTML($_GET[self::$idQuery]);
    } else if (isset($_GET[self::$createPostQuery]) &&
      isset($_GET[self::$idQuery]) && $isLoggedIn) {
      return $this->postView->generatePostHTML($_GET[self::$idQuery]);
    }

    return $this->loginView->response($isLoggedIn);
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

  public function redirectToCreatedThreadPage(int $id) {
    header("Location: http://" . $_SERVER["HTTP_HOST"] . $_SERVER["PHP_SELF"] . "?" . self::$userCreatedThreadQuery .
    "=1&" . self::$idQuery . "=" . $id);
  }

  public function redirectToSamePage() {
    header('Location: http://' . $_SERVER["HTTP_HOST"] . $_SERVER["PHP_SELF"] . '?' . $_SERVER["QUERY_STRING"]);
  }

  public function getBrowser() {
    return $_SERVER["HTTP_USER_AGENT"];
  }
}

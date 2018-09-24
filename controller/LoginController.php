<?php
  class LoginController {
    private $username = "Admin";
    private $password = "Password";

    public function WantToLogin () {
      return isset($_POST["LoginView::Login"]);
    }

    public function CheckLoginCredentials () {
      $enteredUsername = $_POST["LoginView::UserName"];
      $enteredPassword = $_POST["LoginView::Password"];
      if ($enteredUsername == "") {
        $_SESSION["flash"] = "Username is missing";
        $_SESSION["enteredUsername"] = "";
      } else if ($enteredPassword == "") {
        $_SESSION["enteredUsername"] = $enteredUsername;
        $_SESSION["flash"] = "Password is missing";
      } else {
        if (($enteredUsername == $this->username && $enteredPassword != $this->password) ||
        ($enteredUsername != $this->username && $enteredPassword == $this->password) ||
        ($enteredUsername != $this->username && $enteredPassword != $this->password)) {
          $_SESSION["flash"] = "Wrong name or password";
          $_SESSION["enteredUsername"] = $enteredUsername;
        } else if ($enteredUsername == $this->username && $enteredPassword == $this->password) {
          if (!$_SESSION["loggedIn"]) {
            if (isset($_POST["LoginView::KeepMeLoggedIn"])) {
              setcookie("LoginView::CookieName", $enteredUsername, time() + (2 * (60 + 60)));
              setcookie("LoginView::CookiePassword", password_hash($enteredPassword, PASSWORD_BCRYPT), time() + (24 * (60 + 60)));
              $_SESSION["flash"] = "Welcome and you will be remembered";
            } else {
              $_SESSION["flash"] = "Welcome";
            }
            $_SESSION["loggedIn"] = true;
          } else {
             $_SESSION["flash"] = "";
          }
        }
      }
      $location = "";

      if ($_SERVER["HTTP_HOST"] == "localhost") {
        $location = "/1dv610-lab-2";
      }
      header("Location: http://" . $_SERVER["HTTP_HOST"] . $location);
    }

    public function LoginWithCookies ($username, $password) {
      if ($this->username == $username && password_verify($this->password, $password)) {
        $_SESSION["loggedIn"] = true;
        $_SESSION["flash"] = "Welcome back with cookie";
      }
    }

    public function Login($layoutView, $loginView, $dateTimeView) {
      $layoutView->render($_SESSION["loggedIn"], $loginView, $dateTimeView, $message);
    }
  }
?>
<?php
  class LoginController {

    public function WantToLogin () {
      return isset($_POST["LoginView::Login"]);
    }

    public function CheckLoginCredentials () {
      $username = "Admin";
      $password = "Password";
      $enteredUsername = $_POST["LoginView::UserName"];
      $enteredPassword = $_POST["LoginView::Password"];
      if ($enteredUsername == "") {
        $_SESSION["flash"] = "Username is missing";
        $_SESSION["enteredUsername"] = "";
      } else if ($enteredPassword == "") {
        $_SESSION["enteredUsername"] = $_POST["LoginView::UserName"];
        $_SESSION["flash"] = "Password is missing";
      } else {
        if (($enteredUsername == $username && $enteredPassword != $password) ||
        ($enteredUsername != $username && $enteredPassword == $password) ||
        ($enteredUsername != $username && $enteredPassword != $password)) {
          $_SESSION["flash"] = "Wrong name or password";
          $_SESSION["enteredUsername"] = $enteredUsername;
        } else if ($enteredUsername == $username && $enteredPassword == $password) {
          if (!$_SESSION["loggedIn"]) {
            $_SESSION["flash"] = "Welcome";
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

    public function Login($layoutView, $loginView, $dateTimeView) {
      $layoutView->render($_SESSION["loggedIn"], $loginView, $dateTimeView, $message);
    }
  }
?>
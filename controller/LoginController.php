<?php
  class LoginController {

    public function WantToLogin () {
      return isset($_POST["LoginView::Login"]);
    }

    public function CheckLoginCredentials ($con) {
      if ($_POST["LoginView::UserName"] == "") {
        $_SESSION["flash"] = "Username is missing";
        $_SESSION["enteredUsername"] = "";
      } else if ($_POST["LoginView::Password"] == "") {
        $_SESSION["enteredUsername"] = $_POST["LoginView::UserName"];
        $_SESSION["flash"] = "Password is missing";
      } else {
        $_SESSION["flash"] = "";
        $_SESSION["enteredUsername"] = "";
      }
      $location = "";

      if ($_SERVER["HTTP_HOST"] == "localhost") {
        $location = "/1dv610-lab-2";
      }
      header("Location: http://" . $_SERVER["HTTP_HOST"] . $location);
    }

    public function Login(LayoutView $layoutView, LoginView $loginView, DateTimeView $dateTimeView) {
      $message = "";

      if ($_SESSION["flash"] != null) {
        $message = $_SESSION["flash"];
      }
      $layoutView->render(false, $loginView, $dateTimeView, $message);
    }
  }
?>
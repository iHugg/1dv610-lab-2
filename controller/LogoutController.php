<?php
  class LogoutController {
    public function WantToLogout () {
      return isset($_POST["LoginView::Logout"]);
    }

    public function Logout () {
      if ($_SESSION["loggedIn"]) {
        $_SESSION["flash"] = "Bye bye!";
        $_SESSION["loggedIn"] = false;
      } else {
        $_SESSION["flash"] = "";
      }

      setcookie("LoginView::CookieName", "", time() - 3600);
      setcookie("LoginView::CookiePassword", "", time() - 3600);

      header("Location: http://" . $_SERVER["HTTP_HOST"] . $_SERVER["PHP_SELF"]);
    }
  }
?>
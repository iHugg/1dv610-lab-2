<?php
  class LogoutController {
    public function WantToLogout () {
      return isset($_POST["LoginView::Logout"]);
    }

    public function Logout () {
      $_SESSION["loggedIn"] = false;
      $_SESSION["flash"] = "Bye bye!";

      $location = "";

      if ($_SERVER["HTTP_HOST"] == "localhost") {
        $location = "/1dv610-lab-2";
      }
      header("Location: http://" . $_SERVER["HTTP_HOST"] . $location);
    }
  }
?>
<?php
  class Logic {
    public static function Init_Session () {
      $_SESSION["flash"] = "";
      $_SESSION["loggedIn"] = false;
      $_SESSION["enteredUsername"] = "";
    }

    public static function RetrieveUsers ($con) {
      $sql = "SELECT username, password FROM users";
      return $con->query($sql);
    }

    public static function CheckCookie ($con) {
      $browsers = $con->query("SELECT browserName, passwordCookie FROM browsers");

      if ($browsers->num_rows > 0) {
        while ($browser = $browsers->fetch_assoc()) {
          if ($_SERVER["HTTP_USER_AGENT"] == $browser["browserName"] && $_COOKIE["LoginView::CookiePassword"] == $browser["passwordCookie"]) {
            return true;
          }
        }
      }

      return false;
    }
  }
?>
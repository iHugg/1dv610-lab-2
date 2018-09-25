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
  }
?>
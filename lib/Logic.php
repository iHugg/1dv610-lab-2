<?php
  class Logic {
    public static function Init_Session () {
      $_SESSION["flash"] = "";
      $_SESSION["isLoggedIn"] = false;
      $_SESSION["enteredUsername"] = "";
    }
  }
?>
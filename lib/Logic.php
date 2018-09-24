<?php
  class Logic {
    public static function Init_Session () {
      $_SESSION["flash"] = "";
      $_SESSION["loggedIn"] = false;
      $_SESSION["enteredUsername"] = "";
    }
  }
?>
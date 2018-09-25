<?php
  class RegisterController {
    private $username = "Admin";
    private $password = "Password";

    public function CheckRegisterCredentials () {
      $enteredUsername = $_POST["RegisterView::UserName"];
      $enteredPassword = $_POST["RegisterView::Password"];
      $enteredConfirmPassword = $_POST["RegisterView::PasswordRepeat"];
      $minUsernameCharacters = 3;
      $minPasswordCharacters = 6;

      if (strlen($enteredUsername) < $minUsernameCharacters) {
        $_SESSION["flash"] = "Username has too few characters, at least " . $minUsernameCharacters . " characters.<br>";
        $_SESSION["doRegister"] = false;
      }

      $_SESSION["enteredUsername"] = $enteredUsername;

      if (strlen($enteredPassword) < $minPasswordCharacters) {
        $_SESSION["flash"] .= "Password has too few characters, at least " . $minPasswordCharacters . " characters.<br>";
        $_SESSION["doRegister"] = false;
      }

      if ($enteredPassword != $enteredConfirmPassword) {
        $_SESSION["flash"] .= "Passwords do not match.<br>";
      }

      if ($enteredUsername == $this->username) {
        $_SESSION["flash"] .= "User exists, pick another username.";
      }

      header("Location: http://" . $_SERVER["HTTP_HOST"] . $_SERVER["PHP_SELF"] . "?" . $_SERVER["QUERY_STRING"]);
    }
  }
?>
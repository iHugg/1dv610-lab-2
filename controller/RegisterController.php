<?php
  class RegisterController {
    private $username = "Admin";
    private $password = "Password";

    public function CheckRegisterCredentials ($con) {
      $enteredUsername = $_POST["RegisterView::UserName"];
      $enteredPassword = $_POST["RegisterView::Password"];
      $enteredConfirmPassword = $_POST["RegisterView::PasswordRepeat"];
      $minUsernameCharacters = 3;
      $minPasswordCharacters = 6;
      $invalidCharFound = false;
      $failedRegister = false;
      $users = Logic::RetrieveUsers($con);

      if (strlen($enteredUsername) < $minUsernameCharacters) {
        $_SESSION["flash"] = "Username has too few characters, at least " . $minUsernameCharacters . " characters.<br>";
        $_SESSION["doRegister"] = false;
        $failedRegister = true;
      }

      $_SESSION["enteredUsername"] = $enteredUsername;

      if (strlen($enteredPassword) < $minPasswordCharacters) {
        $_SESSION["flash"] .= "Password has too few characters, at least " . $minPasswordCharacters . " characters.<br>";
        $_SESSION["doRegister"] = false;
        $failedRegister = true;
      }

      if ($enteredPassword != $enteredConfirmPassword) {
        $_SESSION["flash"] .= "Passwords do not match.<br>";
        $failedRegister = true;
      }

      if ($users->num_rows > 0) {
        while ($user = $users->fetch_assoc()) {
          if ($enteredUsername == $user["username"]) {
            $_SESSION["flash"] .= "User exists, pick another username.";
            $failedRegister = true;
          }
        }
      }

      foreach (str_split($enteredUsername) as $char) {
        if ($char == '<' || $char == '>') {
          $invalidCharFound = true;
          break;
        }
      }

      //remove the tags and also any other non alphanumerical character
      if ($invalidCharFound) {
        $failedRegister = true;
        $enteredUsername = strip_tags($enteredUsername);
        $_SESSION["enteredUsername"] = preg_replace('/[^A-Za-z0-9\-]/', '', $enteredUsername);
        $_SESSION["flash"] .= "Username contains invalid characters.";
      }

      if ($failedRegister) {
        header("Location: http://" . $_SERVER["HTTP_HOST"] . $_SERVER["PHP_SELF"] . "?" . $_SERVER["QUERY_STRING"]);
      } else {
        $this->Register($con, $enteredUsername, $enteredPassword);
      }
    }

    private function Register ($con, $username, $password) {
      $sql = 'INSERT INTO users (username, password) VALUES ("' . $username . '", "' . password_hash($password, PASSWORD_BCRYPT) . '")';

      if ($con->query($sql) === true) {
        $_SESSION["flash"] = "Registered new user.";
      }

      header("Location: http://" . $_SERVER["HTTP_HOST"] . $_SERVER["PHP_SELF"]);
    }
  }
?>
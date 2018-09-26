<?php
  class LoginController {
    private $username = "Admin";
    private $password = "Password";

    public function WantToLogin () {
      return isset($_POST["LoginView::Login"]);
    }

    public function CheckLoginCredentials ($con) {
      $enteredUsername = $_POST["LoginView::UserName"];
      $enteredPassword = $_POST["LoginView::Password"];
      $userFound = false;
      $users = Logic::RetrieveUsers($con);

      if ($enteredUsername == "") {
        $_SESSION["flash"] = "Username is missing";
        $_SESSION["enteredUsername"] = "";
      } else if ($enteredPassword == "") {
        $_SESSION["enteredUsername"] = $enteredUsername;
        $_SESSION["flash"] = "Password is missing";
      } else {
        if ($users->num_rows > 0) {
          while ($user = $users->fetch_assoc()) {
            if ($enteredUsername == $user["username"] && password_verify($enteredPassword, $user["password"])) {
              $userFound = true;
              if (!$_SESSION["loggedIn"]) {
                if (isset($_POST["LoginView::KeepMeLoggedIn"])) {
                  $passwordHash = password_hash($enteredPassword, PASSWORD_BCRYPT);
                  setcookie("LoginView::CookieName", $enteredUsername, time() + (24 * (60 + 60)));
                  setcookie("LoginView::CookiePassword", $passwordHash, time() + (24 * (60 + 60)));
                  $this->StoreBrowser($con, $passwordHash);
                  $_SESSION["flash"] = "Welcome and you will be remembered";
                } else {
                  $_SESSION["flash"] = "Welcome";
                }
                $_SESSION["loggedIn"] = true;
                break;
              }
            }
          }
        }
        if (!$userFound) {
          $_SESSION["flash"] = "Wrong name or password";
          $_SESSION["enteredUsername"] = $enteredUsername;
        }
      }

      header("Location: http://" . $_SERVER["HTTP_HOST"] . $_SERVER["PHP_SELF"]);
    }

    public function LoginWithCookies ($username, $password) {
      if ($this->username == $username && password_verify($this->password, $password)) {
        $_SESSION["loggedIn"] = true;
        $_SESSION["flash"] = "Welcome back with cookie";
      }
    }

    private function StoreBrowser ($con, $passwordHash) {
      $sql = 'INSERT INTO browsers (browserName, passwordCookie) VALUES ("' . $_SERVER["HTTP_USER_AGENT"] . '", "' . $passwordHash . '")';

      $con->query($sql);
    }
  }
?>
<?php
  class RegisterView {
    private static $name = "RegisterView::UserName";
    private static $password = "RegisterView::Password";
    private static $confirmPassword = "RegisterView::PasswordRepeat";
    private static $messageId = 'RegisterView::Message';
    private static $register = "RegisterView::Register";

    public function generateRegisterFormHTML ($message) {
      return '
      <h2>Register new user</h2>
      <form method="post">
        <fieldset>
          <legend>Register a new user - Write username and password</legend>
          <p id="' . self::$messageId . '">' . $message . '</p>

          <label for="' . self::$name . '">Username: </label>
          <input type="text" id="' . self::$name . '" name="' . self::$name . '" value="' . $_SESSION["enteredUsername"] . '"/><br>

          <label for="' . self::$password . '">Password: </label>
          <input type="password" id="' . self::$password . '" name="' . self::$password . '" value=""/><br>

          <label for="' . self::$confirmPassword . '">Repeat password: </label>
          <input type="password" id="' . self::$confirmPassword . '" name="' . self::$confirmPassword . '" value=""/><br>

          <input type="submit" name="' . self::$register . '" value="register" />
        </fieldset>
      </form>
      ';
    }
  }
?>
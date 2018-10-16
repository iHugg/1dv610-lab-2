<?php
namespace view;

class RegisterView {
  private static $name = "RegisterView::UserName";
  private static $password = "RegisterView::Password";
  private static $repeatPassword = "RegisterView::PasswordRepeat";
  private static $messageId = "RegisterView::Message";
  private static $register = "RegisterView::Register";
  private static $registerValue = "register";
  private $session;

  public function __construct() {
    $this->session = new Session();
  }

  public function generateRegisterFormHTML() : string {
    return '
    <h2>Register new user</h2>
    <form method="post">
      <fieldset>
        <legend>Register a new user - Write username and password</legend>
        <p id="' . self::$messageId . '">' . $this->session->getMessage() . '</p>

        <label for="' . self::$name . '">Username: </label>
        <input type="text" id="' . self::$name . '" name="' . self::$name . '" value="' . $this->session->getEnteredUsername() . '"/><br>

        <label for="' . self::$password . '">Password: </label>
        <input type="password" id="' . self::$password . '" name="' . self::$password . '" value=""/><br>

        <label for="' . self::$repeatPassword . '">Repeat password: </label>
        <input type="password" id="' . self::$repeatPassword . '" name="' . self::$repeatPassword . '" value=""/><br>

        <input type="submit" name="' . self::$register . '" value="' . self::$registerValue . '" />
      </fieldset>
    </form>
    ';
  }

  public function wantsToRegister() : bool {
    return isset($_POST[self::$register]);
  }

  public function getUsername() : string {
    return $_POST[self::$name];
  }

  public function getPassword() : string {
    return $_POST[self::$password];
  }

  public function getRepeatPassword() : string {
    return $_POST[self::$repeatPassword];
  }
}
?>
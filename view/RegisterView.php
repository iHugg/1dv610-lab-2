<?php
  class RegisterView {
    private static $name = "RegisterView::UserName";
    private static $password = "RegisterView::Password";
    private static $confirmPassword = "RegisterView::ConfirmPassword";

    public function GenerateRegisterHTML () {
      return '
      <h2>Register new user</h2>
      <fieldset>
        <legend>Register a new user - Write username and password</legend>
        <label for="' . self::$name . '">Username: </label>
        <input type="text" id="' . self::$name . '" name="' . self::$name . '" value=""/>
        <label for="' . self::$password . '">Password: </label>
        <input type="text" id="' . self::$password . '" name="' . self::$password . '" value=""/>
        <label for="' . self::$confirmPassword . '">Confirm password: </label>
        <input type="text" id="' . self::$confirmPassword . '" name="' . self::$confirmPassword . '" value=""/>
      </fieldset>
      ';
    }
  }
?>
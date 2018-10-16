<?php
namespace model;

class User {
  private $username;
  private $password;
  private $usernameMinLength;
  private $passwordMinLength;

  public function __construct(string $username, string $password) {
    $this->username = $username;
    $this->password = $password;
    $this->usernameMinLength = 3;
    $this->passwordMinLength = 6;
  }

  public function getUsername() : string {
    return $this->username;
  }

  public function getPassword() : string {
    return $this->password;
  }

  public function isUsernameEmpty() : bool {
    return $this->username == "";
  }

  public function isPasswordEmpty() : bool {
    return $this->password == "";
  }

  public function HashedPasswordMatch(string $hashedPassword) : bool {
    return password_verify($this->password, $hashedPassword);
  }

  public function usernameIsTooShort() : bool {
    return strlen($this->username) < $this->usernameMinLength;
  }

  public function passwordIsTooShort() : bool {
    return strlen($this->password) < $this->passwordMinLength;
  }

  public function passwordsMatch(string $otherPassword) : bool {
    return $this->password == $otherPassword;
  }

  public function usernameHasInvalidChar() : bool {
    return preg_match('(<|>)', $this->username) === 1;
  }
}
?>
<?php
namespace model;

class User {
  private $username;
  private $password;
  private $loggedIn;

  public function __construct(string $username, string $password) {
    $this->username = $username;
    $this->password = $password;
  }

  public function getUsername() : string {
    return $this->username;
  }

  public function getPassword() : string {
    return $this->password;
  }

  public function getLoggedIn() : bool {
    return $this->loggedIn;
  }

  public function setLoggedIn(bool $loggedin) {
    $this->loggedIn = $loggedIn;
  }

  public function isUsernameEmpty() : bool {
    return $this->username == "";
  }

  public function isPasswordEmpty() : bool {
    return $this->password == "";
  }
}
?>
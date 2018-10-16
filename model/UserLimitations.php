<?php
namespace model;

class UserLimitations {
  private $usernameMinLength;
  private $passwordMinLength;

  public function __construct() {
    $this->usernameMinLength = 3;
    $this->passwordMinLength = 6;
  }

  public function getUsernameMinLength() {
    return $this->usernameMinLength;
  }

  public function getPasswordMinLength() {
    return $this->passwordMinLength;
  }
}
?>
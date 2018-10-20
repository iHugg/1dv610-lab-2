<?php
namespace view;

class BaseView {
  protected static $idQuery = "id";
  protected static $userCreatedThreadQuery = "user-created-thread";
  protected static $createThreadQuery = "createThread";
  protected static $createPostQuery = "create-post";
  protected $session;
  protected $threadSQL;

  public function __construct(\mysqli $connection) {
    $this->session = new Session();
    $this->threadSQL = new \model\ThreadSQL($connection);
  }
}
?>
<?php
namespace controller;

class ThreadController extends BaseController {

  public function __construct(\mysqli $connection) {
    parent::__construct($connection);
  }
  
  public function createThread() {
    $title = $this->threadView->getTitle();
    $this->threadSQL->saveNewThread($title, $this->session->getUsername());
    $this->layoutView->redirectToThreadPage();
  }
}
?>
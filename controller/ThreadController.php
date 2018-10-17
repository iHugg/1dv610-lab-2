<?php
namespace controller;

class ThreadController extends BaseController {
  private $threadDatabase;

  public function __construct() {
    parent::__construct();
    $this->threadDatabase = new \model\ThreadDatabase();
  }
  public function createThread() {
    $title = $this->threadView->getTitle();
    $this->threadDatabase->saveThread($title);
    $this->layoutView->redirectToThreadPage();
  }
}
?>
<?php
namespace controller;

class ThreadController extends BaseController {

  public function __construct(\mysqli $connection) {
    parent::__construct($connection);
  }
  
  public function createThread() {
    $title = $this->threadView->getTitle();
    $maxId = $this->threadSQL->getMaxId();
    $author = $this->session->getUsername();
    $thread = new \model\Thread($title, $author, ++$maxId, 0);
    $this->threadSQL->saveNewThread($thread);
    $this->layoutView->redirectToThreadPage();
  }

  public function deleteThread() {
    $threadId = $this->threadView->getThreadIdToDelete();
    $this->threadSQL->deleteThread($threadId);
    $this->layoutView->redirectToThreadPage();
  }
}
?>
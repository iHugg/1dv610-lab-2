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

    if (!$this->titleHasErrors($thread)) {
      $this->threadSQL->saveNewThread($thread);
      $this->sessionPrinter->emptyTitle();
      $this->layoutView->redirectToThreadPage();
    } else {
      $this->sessionPrinter->setThreadTitle($thread);
      $this->layoutView->redirectToSamePage();
    }
  }

  public function deleteThread() {
    $threadId = $this->threadView->getThreadIdToDelete();
    $this->threadSQL->deleteThread($threadId);
    $this->layoutView->redirectToThreadPage();
  }

  private function titleHasErrors(\model\Thread $thread) : bool {
    $errorFound = false;
    if ($thread->titleIsTooShort()) {
      $this->sessionPrinter->titleIsTooShort($thread);
      $errorFound = true;
    }

    if ($thread->titleIsTooLong()) {
      $this->sessionPrinter->titleIsTooLong($thread);
      $errorFound = true;
    }

    if ($thread->titleContainsInvalidChar()) {
      $this->sessionPrinter->titleContainsInvalidChar();
      $cleanTitle = $this->sessionPrinter->removeTagsAndInvalidCharacters($thread->getTitle());
      $thread->setTitle($cleanTitle);
      $errorFound = true;
    }

    return $errorFound;
  }
}
?>
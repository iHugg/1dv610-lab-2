<?php
namespace controller;

class PostController extends BaseController {
  public function __construct(\mysqli $connection) {
    parent::__construct($connection);
  }

  public function savePost() {
    $threadId = $this->getThreadId();
    $thread = $this->threadSQL->getThread($threadId);
    $postText = $this->postView->getPost();
    $postMaxId = $thread->getMaxPostId();
    $post = new \model\Post($postText, $this->session->getUsername(), ++$postMaxId);

    if (!$this->postHasErrors($post)) {
      $thread->addPost($post);
      $this->threadSQL->savePosts($thread);
      $this->sessionPrinter->emptyPost();
      $this->layoutView->redirectToCreatedThreadPage($threadId);
    } else {
      $this->sessionPrinter->setPost($post);
      $this->layoutView->redirectToSamePage();
    }
  }

  public function deletePost() {
    $postId = $this->threadView->getPostId();
    $threadId = $this->threadView->getIdFromURL();
    $thread = $this->threadSQL->getThread($threadId);
    $thread->deletePost($postId);
    $this->threadSQL->savePosts($threadId, $thread->getJsonPosts());
    $this->layoutView->redirectToCreatedThreadPage($threadId);
  }

  private function getThreadId() : int {
    return $this->postView->getIdFromURL();
  }

  private function addPosts(\model\Thread $thread) {
    $posts = $this->threadSQL->getPosts($thread->getTitle());
    $thread->addPostsFromDatabase($posts);
  }

  private function postHasErrors(\model\Post $post) : bool {
    $errorFound = false;

    if ($post->postIsEmpty()) {
      $this->sessionPrinter->postIsEmpty();
      $errorFound = true;
    }

    if ($post->postContainsInvalidChar()) {
      $cleanPost = $this->sessionPrinter->removeTagsAndInvalidCharacters($post->getPost());
      $post->setPost($cleanPost);
      $this->sessionPrinter->postContainsInvalidChar();
      $errorFound = true;
    }

    return $errorFound;
  }
}
?>
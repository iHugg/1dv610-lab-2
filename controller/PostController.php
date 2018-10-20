<?php
namespace controller;

/**
 * Handles everything regarding posts.
 */
class PostController extends BaseController {
  public function __construct(\mysqli $connection) {
    parent::__construct($connection);
  }

  public function savePost() {
    $threadId = $this->getThreadId();
    $thread = $this->getThread($threadId);
    $post = $this->getPost($thread);

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

  private function getThread(int $threadId) : \model\Thread {
    return $this->threadSQL->getThread($threadId);
  }

  private function getPost(\model\Thread $thread) : \model\Post {
    $postText = $this->postView->getPost();
    $postMaxId = $thread->getMaxPostId();
    return new \model\Post($postText, $this->session->getUsername(), ++$postMaxId);
  }

  public function deletePost() {
    $postId = $this->postView->getPostId();
    $threadId = $this->getThreadId();
    $thread = $this->getThread($threadId);
    $thread->deletePost($postId);
    $this->threadSQL->savePosts($thread);
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
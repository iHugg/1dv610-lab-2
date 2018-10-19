<?php
namespace controller;

class PostController extends BaseController {
  public function __construct(\mysqli $connection) {
    parent::__construct($connection);
  }

  public function savePost() {
    $threadId = $this->getThreadId();
    $thread = $this->threadSQL->getThread($threadId);
    $post = $this->postView->getPost();
    $thread->addPost($post, $this->session->getUsername());
    $this->threadSQL->savePosts($thread);
    $this->layoutView->redirectToCreatedThreadPage($threadId);
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
}
?>
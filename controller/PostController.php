<?php
namespace controller;

class PostController extends BaseController {
  public function __construct(\mysqli $connection) {
    parent::__construct($connection);
  }

  public function savePost() {
    $title = $this->getThreadTitle();
    $thread = new \model\Thread($title);
    $this->addPosts($thread);
    $post = $this->postView->getPost();
    $thread->addPost($post, $this->session->getUsername());
    $this->threadSQL->savePosts($title, $thread->getJsonPosts());
    $this->layoutView->redirectToCreatedThreadPage($title);
  }

  public function deletePost() {
    $postId = $this->threadView->getPostId();
    $title = $this->getThreadTitle();
    $thread = new \model\Thread($title);
    $this->addPosts($thread);
    $thread->deletePost($postId);
    $this->threadSQL->savePosts($title, $thread->getJsonPosts());
    $this->layoutView->redirectToCreatedThreadPage($title);
  }

  private function getThreadTitle() : string {
    return $this->postView->getTitle();
  }

  private function addPosts(\model\Thread $thread) {
    $posts = $this->threadSQL->getPosts($thread->getTitle());
    $thread->addPostsFromDatabase($posts);
  }
}
?>
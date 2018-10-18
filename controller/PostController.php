<?php
namespace controller;

class PostController extends BaseController {
  public function __construct(\mysqli $connection) {
    parent::__construct($connection);
  }

  public function savePost() {
    $title = $this->postView->getTitle();
    $thread = new \model\Thread($title);
    $posts = $this->threadSQL->getPosts($title);
    $thread->addPostsFromDatabase($posts);
    $post = $this->postView->getPost();
    $thread->addPost($post, $this->session->getUsername());
    $this->threadSQL->savePosts($title, $thread->getJsonPosts());
    $this->session->setMessage($thread->getJsonPosts());
    $this->layoutView->redirectToCreatedThreadPage($title);
  }
}
?>
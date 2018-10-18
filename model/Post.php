<?php
namespace model;

class Post {
  public $post;
  public $author;

  public function __construct(string $post, string $author) {
    $this->post = $post;
    $this->author = $author;
  }
}
?>
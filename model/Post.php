<?php
namespace model;

class Post {
  public $post;
  public $author;
  public $id;

  public function __construct(string $post, string $author, int $id) {
    $this->post = $post;
    $this->author = $author;
    $this->id = $id;
  }
}
?>
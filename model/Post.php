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

  public function getPost() : string {
    return $this->post;
  }

  public function getAuthor() : string {
    return $this->author;
  }

  public function getId() : int {
    return $this->id;
  }
}
?>
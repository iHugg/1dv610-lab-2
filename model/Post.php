<?php
namespace model;

class Post {
  public $post;

  public function __construct(string $post) {
    $this->post = $post;
  }
}
?>
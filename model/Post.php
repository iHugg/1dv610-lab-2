<?php
namespace model;

/**
 * The model representing a post.
 * Need the fields to be public, otherwise json_encode can't access them.
 * It needs to access them when saving posts to the database.
 */
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

  public function setPost(string $post) {
    $this->post = $post;
  }

  public function postIsEmpty() : bool {
    return strlen($this->post) == 0;
  }

  public function postContainsInvalidChar() : bool {
    return preg_match('(<|>)', $this->post) === 1;
  }
}
?>
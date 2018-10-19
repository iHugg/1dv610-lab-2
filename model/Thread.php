<?php
namespace model;

class Thread {
  private $title;
  private $posts;
  private $author;
  private $id;
  private $postCount;
  private $titleMinChar;
  private $titleMaxChar;

  public function __construct(string $title, string $author, int $id, int $postCount) {
    $this->title = $title;
    $this->id = $id;
    $this->author = $author;
    $this->postCount = $postCount;
    $this->posts = array();
    $this->titleMinChar = 4;
    $this->titleMaxChar = 70;
  }

  public function getTitle() : string {
    return $this->title;
  }

  public function setTitle(string $title) {
    $this->title = $title;
  }

  public function getAuthor() : string {
    return $this->author;
  }

  public function getId() : int {
    return $this->id;
  }

  public function getPostCount() : int {
    return $this->postCount;
  }

  public function addPostsFromDatabase(string $json) {
    $posts = json_decode($json, true);

    foreach($posts as $post) {
      $this->posts[] = new Post($post["post"], $post["author"], $post["id"]);
    }
  }

  public function addPost(Post $post) {
    $this->posts[] = $post;
  }

  public function getMaxPostId() : int {
    $max = -1;

    foreach($this->posts as $post) {
      if ($post->getId() > $max) {
        $max = $post->getId();
      }
    }

    return $max;
  }

  public function getPosts() : array {
    return $this->posts;
  }

  public function getJsonPosts() : string {
    return json_encode($this->posts);
  }

  public function deletePost($id) {
    $index = $this->getPostToDeleteIndex($id);
    if ($index != -1) {
      array_splice($this->posts, $index, 1);
    }
  }

  private function getPostToDeleteIndex($id) : int {
    $index = -1;
    for ($i = 0; $i < count($this->posts); $i++) {
      if ($this->posts[$i]->getId() == $id) {
        $index = $i;
        break;
      }
    }
    return $index;
  }

  public function titleIsTooShort() : bool {
    return strlen($this->title) < $this->titleMinChar;
  }

  public function titleIsTooLong() : bool {
    return strlen($this->title) > $this->titleMaxChar;
  }

  public function titleContainsInvalidChar() : bool {
    return preg_match('(<|>)', $this->title) === 1;
  }

  public function getTitleMinChar() : int {
    return $this->titleMinChar;
  }

  public function getTitleMaxChar() : int {
    return $this->titleMaxChar;
  }
}
?>
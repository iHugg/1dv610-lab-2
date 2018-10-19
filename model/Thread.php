<?php
namespace model;

class Thread {
  private $title;
  private $posts;
  private $author;
  private $id;

  public function __construct(string $title, string $author, int $id) {
    $this->title = $title;
    $this->id = $id;
    $this->author = $author;
    $this->posts = array();
  }

  public function getTitle() : string {
    return $this->title;
  }

  public function getAuthor() {
    return $this->author;
  }

  public function getId() {
    return $this->id;
  }

  public function addPostsFromDatabase(string $json) {
    $posts = json_decode($json, true);

    foreach($posts as $post) {
      $this->posts[] = new Post($post["post"], $post["author"], $post["id"]);
    }
  }

  public function addPost(string $post, string $author) {
    $maxId = $this->getMaxPostId();
    $this->posts[] = new Post($post, $author, ++$maxId);
  }

  private function getMaxPostId() : int {
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
}
?>
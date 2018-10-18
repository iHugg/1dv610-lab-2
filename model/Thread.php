<?php
namespace model;

class Thread {
  private $title;
  private $posts;

  public function __construct(string $title) {
    $this->title = $title;
    $this->posts = array();
  }

  public function getTitle() : string {
    return $this->title;
  }

  public function addPostsFromDatabase(string $json) {
    $posts = json_decode($json, true);

    foreach($posts as $key => $post) {
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
      if ($post->id > $max) {
        $max = $post->id;
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
      if ($this->posts[$i]->id == $id) {
        $index = $i;
        break;
      }
    }
    return $index;
  }
}
?>
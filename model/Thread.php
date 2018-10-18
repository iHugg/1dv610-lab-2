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
      $this->posts[] = new Post($post["post"], $post["author"]);
    }
  }

  public function addPost(string $post, string $author) {
    $this->posts[] = new Post($post, $author);
  }

  public function getPosts() : array {
    return $this->posts;
  }

  public function getJsonPosts() : string {
    return json_encode($this->posts);
  }
}
?>
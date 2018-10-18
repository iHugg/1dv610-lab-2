<?php
namespace model;

class ThreadSQL {
  private $connection;

  public function __construct(\mysqli $connection) {
    $this->connection = $connection;
  }

  public function saveNewThread(string $threadTitle) : bool {
    $sql = 'INSERT INTO threads (title) VALUES ("' . $threadTitle . '")';
    return $this->connection->query($sql);
  }

  public function getThreads() : array {
    $threads = array();
    $sql = 'SELECT title FROM threads';
    $result = $this->connection->query($sql);
    if ($result->num_rows > 0) {
      while ($thread = $result->fetch_assoc()) {
        $threads[] = $thread["title"];
      }
    }

    return $threads;
  }

  public function getPosts(string $title) : string {
    $sql = 'SELECT posts FROM threads WHERE title = "' . $title . '"';
    $result = $this->connection->query($sql);

    if ($result->num_rows == 1) {
      $json = $result->fetch_assoc();
      return $json["posts"];
    }

    return "";
  }

  public function savePosts(string $title, string $posts) {
    $posts = addslashes($posts);
    $sql = 'REPLACE INTO threads (title, posts) VALUES ("' . $title . '", "' . $posts . '")';
    return $this->connection->query($sql);
  }
}
?>
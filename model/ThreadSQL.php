<?php
namespace model;

class ThreadSQL {
  private $connection;

  public function __construct(\mysqli $connection) {
    $this->connection = $connection;
  }

  public function saveNewThread(Thread $thread) : bool {
    $sql = 'INSERT INTO threads (title, threadAuthor, threadId) VALUES ("' . $thread->getTitle() . '", "' . $thread->getAuthor() . '", "' . $thread->getId() . '")';
    return $this->connection->query($sql);
  }

  public function getThreads() : array {
    $threads = array();
    $sql = 'SELECT title, threadAuthor, threadId, postCount FROM threads';
    $result = $this->connection->query($sql);
    if ($result->num_rows > 0) {
      while ($thread = $result->fetch_assoc()) {
        $threads[] = new Thread($thread["title"], $thread["threadAuthor"], $thread["threadId"], $thread["postCount"]);
      }
    }

    return $threads;
  }

  public function savePosts(Thread $thread) {
    $jsonPosts = $thread->getJsonPosts();
    $jsonPosts = addslashes($jsonPosts);
    $sql = 'UPDATE threads SET posts="' . $jsonPosts . '", postCount = "' . count($thread->getPosts()) . '" WHERE threadId="' . $thread->getId() . '"';
    return $this->connection->query($sql);
  }

  public function getMaxId() : int {
    $sql = 'SELECT MAX(threadId) AS "maxId" FROM threads';
    $result = $this->connection->query($sql);
    $maxId = -1;

    if ($result->num_rows == 1) {
      $result = $result->fetch_assoc();
      $maxId = $result["maxId"];
    }

    if ($maxId == null) {
      $maxId = -1;
    }

    return $maxId;
  }

  public function getTitle(int $id) : string {
    $sql = 'SELECT title FROM threads WHERE threadId="' . $id . '"';
    $title = "";
    $result = $this->connection->query($sql);

    if ($result->num_rows == 1) {
      $result = $result->fetch_assoc();

      if (isset($result["title"])) {
        $title = $result["title"];
      }
    }

    return $title;
  }

  public function getThread(int $id) : Thread {
    $sql = 'SELECT title, threadAuthor, postCount, posts FROM threads WHERE threadId="' . $id . '"';
    $result = $this->connection->query($sql);
    $thread = null;

    if ($result->num_rows == 1) {
      $result = $result->fetch_assoc();
      $thread = new Thread($result["title"], $result["threadAuthor"], $id, $result["postCount"]);
      if ($result["posts"] != null) {
        $thread->addPostsFromDatabase($result["posts"]);
      }
    }

    return $thread;
  }

  public function deleteThread(int $id) : bool {
    $sql = 'DELETE FROM threads WHERE threadId="' . $id . '"';
    return $this->connection->query($sql);
  }
}
?>
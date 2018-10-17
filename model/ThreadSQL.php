<?php
namespace model;

class ThreadSQL {
  private $connection;

  public function __construct(\mysqli $connection) {
    $this->connection = $connection;
  }

  public function saveThread(string $threadTitle) : bool {
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
}
?>
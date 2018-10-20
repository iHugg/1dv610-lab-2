<?php
namespace model;

class ThreadSQL {
  private static $tableName = "threads";
  private static $titleName = "title";
  private static $authorName = "threadAuthor";
  private static $idName = "threadId";
  private static $postCountName = "postCount";
  private static $postsName = "posts";
  private $connection;

  public function __construct(\mysqli $connection) {
    $this->connection = $connection;
  }

  public function saveNewThread(Thread $thread) : bool {
    $sql = 'INSERT INTO ' . self::$tableName . ' (' . self::$titleName . ', ' . self::$authorName . ', ' . self::$idName . ') 
    VALUES ("' . $thread->getTitle() . '", "' . $thread->getAuthor() . '", "' . $thread->getId() . '")';
    return $this->connection->query($sql);
  }

  public function getThreads() : array {
    $threads = array();
    $sql = 'SELECT ' . self::$titleName . ', ' . self::$authorName . ', ' . self::$idName . ', ' . self::$postCountName . ' FROM ' . self::$tableName;
    $result = $this->connection->query($sql);
    if ($result->num_rows > 0) {
      while ($thread = $result->fetch_assoc()) {
        $threads[] = new Thread($thread[self::$titleName], $thread[self::$authorName], $thread[self::$idName], $thread[self::$postCountName]);
      }
    }

    return $threads;
  }

  public function savePosts(Thread $thread) {
    $jsonPosts = $thread->getJsonPosts();
    $jsonPosts = addslashes($jsonPosts);
    $sql = 'UPDATE ' . self::$tableName . ' SET ' . self::$postsName . '="' . $jsonPosts . '", 
    ' . self::$postCountName . '="' . count($thread->getPosts()) . '" WHERE ' . self::$idName . '="' . $thread->getId() . '"';
    return $this->connection->query($sql);
  }

  public function getMaxId() : int {
    $sql = 'SELECT MAX(' . self::$idName . ') AS "maxId" FROM ' . self::$tableName;
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
    $sql = 'SELECT ' . self::$titleName . ' FROM ' . self::$tableName . ' WHERE ' . self::$idName . '="' . $id . '"';
    $title = "";
    $result = $this->connection->query($sql);

    if ($result->num_rows == 1) {
      $result = $result->fetch_assoc();

      if (isset($result[self::$titleName])) {
        $title = $result[self::$titleName];
      }
    }

    return $title;
  }

  public function getThread(int $id) : Thread {
    $sql = 'SELECT ' . self::$titleName . ', ' . self::$authorName . ', ' . self::$postCountName . ', ' . self::$postsName . ' 
    FROM ' . self::$tableName . ' WHERE ' . self::$idName . '="' . $id . '"';
    $result = $this->connection->query($sql);
    $thread = null;

    if ($result->num_rows == 1) {
      $result = $result->fetch_assoc();
      $thread = new Thread($result[self::$titleName], $result[self::$authorName], $id, $result[self::$postCountName]);
      if ($result[self::$postsName] != null) {
        $thread->addPostsFromDatabase($result[self::$postsName]);
      }
    }

    if ($thread == null) {
      throw new \Exception();
    }

    return $thread;
  }

  public function deleteThread(int $id) : bool {
    $sql = 'DELETE FROM ' . self::$tableName . ' WHERE ' . self::$idName . '="' . $id . '"';
    return $this->connection->query($sql);
  }
}
?>
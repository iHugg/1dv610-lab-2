<?php
namespace view;

class ThreadView {
  private static $messageId = "ThreadView::Message";
  private static $threadTitle = "ThreadView::Title";
  private static $createThread = "TitleView::CreateThread";
  private static $createThreadValue = "Create Thread";
  private static $createdThreadQuery = "user-created-thread";
  private static $createPostQuery = "create-post";
  private static $deletePost = "ThreadView::DeletePost";
  private static $deleteThread = "ThreadView::DeleteThread";
  private static $threadIdName = "ThreadView::ThreadId";
  private static $postIdName = "ThreadView::PostId";
  private $threadSQL;
  private $session;
  private $createThreadQuery;

  public function __construct(\mysqli $connection) {
    $this->threadSQL = new \model\ThreadSQL($connection);
    $this->session = new Session();
    $this->createThreadQuery = "createThread";
  }

  public function getCreateThreadQuery() : string {
    return $this->createThreadQuery;
  }

  public function generateThreadHTML() : string {
    return '
    <h2>Threads</h2>
    ' . $this->generateCreateThreadLink() . '
    ';
  }

  public function generateCreateThreadLink() : string {
    $location = $this->getLocation();
    $html = '<a href="' . $location . '/index.php?' . $this->createThreadQuery . '=1">Create new thread</a><br>';

    if (!$this->session->isLoggedIn()) {
      $html = "";
    }

    $html .= $this->generateThreadTitlesHTML();

    return $html;
  }

  public function generateCreateThreadHTML() : string {
    return '
    <h2>Create new thread</h2>
    <form method="post">
      <fieldset>
        <legend>Create new thread - Enter thread title</legend>
        <p id="' . self::$messageId . '">' . $this->session->getMessage() . '</p>
        <label for="' . self::$threadTitle . '">Title: </label>
        <input type="text" id="' . self::$threadTitle . '" name="' . self::$threadTitle . '" value="' . $this->session->getThreadTitle() . '" size="100"/><br>
        <input type="submit" name="' . self::$createThread . '" value="' . self::$createThreadValue . '" />
      </fieldset>
    </form>
    ';
  }

  private function generateThreadTitlesHTML() {
    $threads = $this->threadSQL->getThreads();
    $submitHtml = '<input type="submit" name="' . self::$deleteThread . '" value="Delete post">';
    $html = '';

    if (count($threads) > 0) {
      $html .= '
      <fieldset>
      <legend>Existing threads</legend>
      ';
      foreach($threads as $thread) {
        $html .= '
        <fieldset>
          <legend>Created by: ' . $thread->getAuthor() . ' - Number of posts: ' . $thread->getPostCount() . '</legend>
          <a href="' . $this->getLocation() . '/index.php?' . self::$createdThreadQuery . '=1&id=' . $thread->getId() . '" style="margin:10px;">' . $thread->getTitle() . '</a>
          ' . $this->generateDeleteThreadHTML($thread) . '
        </fieldset>
        ';
      }
    }
    return $html;
  }

  private function generateDeleteThreadHTML(\model\Thread $thread) {
    $submit = '';

    if ($thread->getAuthor() == $this->session->getUsername() || $this->session->getUsername() == "Admin") {
      $submit = '
      <form method="post">
        <input type="hidden" id="' . self::$threadIdName . '" name="' . self::$threadIdName . '" value="' . $thread->getId() . '" />
        <input type="submit" name="' . self::$deleteThread . '" value="Delete thread" style="float:right;">
      </form>
      ';
    }

    return $submit;
  }

  public function generateUserCreatedThreadHTML(int $id) : string {
    $title = $this->threadSQL->getTitle($id);
    $createPostHtml = '<a href="' . $this->getLocation() . '/index.php?' . self::$createPostQuery . '=1&id=' . $id . '">Create post</a>';

    if (!$this->session->isLoggedIn()) {
      $createPostHtml = "";
    }
    return '
    <h2>' . $title . '</h2>
    ' . $createPostHtml . '
    ' . $this->getPosts() . '
    ';
  }

  private function getPosts() : string {
    $thread = $this->threadSQL->getThread($this->getIdFromURL());
    $postHtml = "";
    $submitHtml = '<input type="submit" name="' . self::$deletePost . '" value="Delete post" style="float:right;">';
    $submit = "";

    if ($thread != null) {
      foreach ($thread->getPosts() as $key => $post) {
        if ((!$this->session->isLoggedIn() || $this->session->getUsername() != $post->getAuthor()) && $this->session->getUsername() != "Admin" &&
        $this->session->getUsername() != $thread->getAuthor()) {
          $submit = "";
        } else {
          $submit = '
          <input type="hidden" id="' . self::$postIdName . '" name="' . self::$postIdName . '" value="' . $post->getId() . '"/>
          ' . $submitHtml . '
          ';
        }

        $postHtml .= '
        <form method="post">
          <fieldset>
            <legend>' . $post->getAuthor() . '</legend>
            <p>' . $post->getPost() . '</p>
            ' . $submit . '
          </fieldset>
        </form>
        ';
      }
    }

    return $postHtml;
  }

  private function getLocation() {
    if ($_SERVER["HTTP_HOST"] == "localhost") {
      return "/1dv610-lab-2";
    }

    return "";
  }

  public function wantsToCreateThread() : bool {
    return isset($_POST[self::$threadTitle]);
  }

  public function wantsToDeleteThread() : bool {
    return isset($_POST[self::$deleteThread]);
  }

  public function wantsToDeletePost() : bool {
    return isset($_POST[self::$deletePost]);
  }

  public function getThreadIdToDelete() : int {
    return $_POST[self::$threadIdName];
  }

  public function getPostId() : int {
    return $_POST[self::$postIdName];
  }

  public function getTitle() : string {
    return $_POST[self::$threadTitle];
  }

  public function getIdFromURL() : int {
    parse_str($_SERVER["QUERY_STRING"], $result);
    
    if (isset($result["id"])) {
      return $result["id"];
    }

    return -1;
  }

  public function getCreatedThreadQuery() : string {
    return self::$createdThreadQuery;
  }

  public function getCreatePostQuery() : string {
    return static::$createPostQuery;
  }
}
?>
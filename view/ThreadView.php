<?php
namespace view;

class ThreadView {
  private static $messageId = "ThreadView::Message";
  private static $threadTitle = "ThreadView::Title";
  private static $createThread = "TitleView::CreateThread";
  private static $createThreadValue = "Create Thread";
  private static $createdThreadQuery = "user-created-thread";
  private static $createPostQuery = "create-post";
  private static $deletePost = "ThreadView::RemovePost";
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
        <input type="text" id="' . self::$threadTitle . '" name="' . self::$threadTitle . '" value=""/><br>
        <input type="submit" name="' . self::$createThread . '" value="' . self::$createThreadValue . '" />
      </fieldset>
    </form>
    ';
  }

  private function generateThreadTitlesHTML() {
    $threads = $this->getSavedTitles();
    $html = '';

    if (count($threads) > 0) {
      $html .= '
      <fieldset>
      <legend>Existing threads</legend>
      ';
      foreach($threads as $thread) {
        $html .= '<a href="' . $this->getLocation() . '/index.php?' . self::$createdThreadQuery . '=1&title=' . $thread . '" style="margin:10px;">' . $thread . '</a>';
      }
    }
    return $html;
  }

  public function generateUserCreatedThreadHTML(string $title) : string {
    $createPostHtml = '<a href="' . $this->getLocation() . '/index.php?' . self::$createPostQuery . '=1&title=' . $title . '">Create post</a>';

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
    $posts = $this->threadSQL->getPosts($this->getTitleFromURL());
    $postHtml = "";
    $submitHtml = '<input type="submit" name="' . self::$deletePost . '" value="Delete post">';
    $submit = "";
    $threadAuthor = $this->threadSQL->getThreadAuthor($this->getTitleFromURL());

    if ($posts != "") {
      $posts = json_decode($posts, true);

      foreach ($posts as $key => $post) {
        if ((!$this->session->isLoggedIn() || $this->session->getUsername() != $post["author"]) && $this->session->getUsername() != "Admin" &&
        $this->session->getUsername() != $threadAuthor) {
          $submit = "";
        } else {
          $submit = '
          <input type="hidden" id="' . self::$postIdName . '" name="' . self::$postIdName . '" value="' . $post["id"] . '"/>
          ' . $submitHtml . '
          ';
        }

        $postHtml .= '
        <form method="post">
          <fieldset>
            <legend>' . $post["author"] . '</legend>
            <p>' . $post["post"] . '</p>
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

  public function wantsToDeletePost() : bool {
    return isset($_POST[self::$deletePost]);
  }

  public function getPostId() : int {
    return $_POST[self::$postIdName];
  }

  public function getTitle() : string {
    return $_POST[self::$threadTitle];
  }

  private function getTitleFromURL() : string {
    parse_str($_SERVER["QUERY_STRING"], $result);
    
    if (isset($result["title"])) {
      return $result["title"];
    }

    return "";
  }

  public function getCreatedThreadQuery() : string {
    return self::$createdThreadQuery;
  }

  public function getCreatePostQuery() : string {
    return static::$createPostQuery;
  }

  private function getSavedTitles() {
    return $this->threadSQL->getThreads();
  }
}
?>
<?php
namespace view;

/**
 * Handles the html for the thread related pages.
 * It was hard to come up with names for all the methods.
 * They all sound somewhat similar.
 */
class ThreadView extends BaseView {
  private static $messageId = "ThreadView::Message";
  private static $threadTitle = "ThreadView::Title";
  private static $createThread = "ThreadView::CreateThread";
  private static $deleteThread = "ThreadView::DeleteThread";
  private static $threadIdName = "ThreadView::ThreadId";
  private static $createThreadValue = "Create thread";

  private $postView;

  public function __construct(\mysqli $connection) {
    parent::__construct($connection);
    $this->postView = new PostView($connection);
  }

  public function generateThreadHTML() : string {
    return '
    <h2>Threads</h2>
    ' . $this->generateCreateThreadLink() . '
    ';
  }

  private function generateCreateThreadLink() : string {
    $location = $this->getLocation();
    $html = '<a href="' . $location . '/index.php?' . self::$createThreadQuery . '=1">Create new thread</a><br>';

    if (!$this->session->isLoggedIn()) {
      $html = "<p>You need to be logged in to create threads.</p>";
    }

    $html .= $this->generateThreadTitlesHTML();

    return $html;
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
          <a href="' . $this->getLocation() . '/index.php?' . self::$userCreatedThreadQuery . '=1&' . self::$idQuery . 
          '=' . $thread->getId() . '" style="margin:10px;">' . $thread->getTitle() . '</a>
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

  public function generateCreateThreadHTML() : string {
    return '
    <h2>Create new thread</h2>
    <form method="post">
      <fieldset>
        <legend>Create new thread - Enter thread title</legend>
        <p id="' . self::$messageId . '">' . $this->session->getMessage() . '</p>
        <label for="' . self::$threadTitle . '">Title: </label>
        <input type="text" id="' . self::$threadTitle . '" name="' . self::$threadTitle . '" value="' . 
        $this->session->getThreadTitle() . '" size="100"/><br>
        <input type="submit" name="' . self::$createThread . '" value="' . self::$createThreadValue . '" />
      </fieldset>
    </form>
    ';
  }

  public function generateUserCreatedThreadHTML(int $id) : string {
    $title = $this->threadSQL->getTitle($id);
    $createPostHtml = '<a href="' . $this->getLocation() . '/index.php?' . self::$createPostQuery . 
    '=1&' . self::$idQuery . '=' . $id . '">Create post</a>';
    $thread = null;
    try {
      $thread = $this->threadSQL->getThread($id);
    } catch (\Exception $ex) {
      return '
      <h1>No such thread was found!</h1>
      ';
    }

    if (!$this->session->isLoggedIn()) {
      $createPostHtml = "<p>You need to be logged in to create posts.</p>";
    }

    return '
    <h2>' . $title . '</h2>
    ' . $createPostHtml . '
    ' . $this->postView->getPosts($thread) . '
    ';
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

  public function getThreadIdToDelete() : int {
    return $_POST[self::$threadIdName];
  }

  public function getTitle() : string {
    return $_POST[self::$threadTitle];
  }

  public function getIdFromURL() : int {
    return $_GET[self::$idQuery];
  }
}
?>
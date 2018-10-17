<?php
namespace view;

class ThreadView {
  private static $messageId = "ThreadView::Message";
  private static $threadTitle = "ThreadView::Title";
  private static $createThread = "TitleView::CreateThread";
  private static $createThreadValue = "createThread";
  private $threadSQL;
  private $session;
  private $createThreadQuery;

  public function __construct(\mysqli $connection) {
    $this->threadSQL = new \model\ThreadSQL($connection);
    $this->session = new Session();
    $this->createThreadQuery = "createThread=1";
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
    $html = '<a href="' . $location . '/index.php?' . $this->createThreadQuery . '">Create new thread</a><br>';
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
        $html .= '<a href="' . $this->getLocation() . '/index.php?' . $thread . '=1" style="margin:10px;">' . $thread . '</a>';
      }
    }
    return $html;
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

  public function getTitle() : string {
    return $_POST[self::$threadTitle];
  }

  private function getSavedTitles() {
    return $this->threadSQL->getThreads();
  }
}
?>
<?php
namespace view;

class ThreadView {
  private static $messageId = "ThreadView::Message";
  private static $threadTitle = "ThreadView::Title";
  private static $createThread = "TitleView::CreateThread";
  private static $createThreadValue = "createThread";
  private $session;
  private $createThreadQuery;

  public function __construct() {
    $this->session = new Session();
    $this->createThreadQuery = "createThread=1";
  }

  public function getCreateThreadQuery() {
    return $this->createThreadQuery;
  }

  public function generateThreadHTML() {
    return '
    <h2>Threads</h2>
    ' . $this->generateCreateThreadLink() . '
    ';
  }

  public function generateCreateThreadLink() {
    $location = $this->getLocation();

    return '<a href="' . $location . '/index.php?' . $this->createThreadQuery . '">Create new thread</a>';
  }

  public function generateCreateThreadHTML() {
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
}
?>
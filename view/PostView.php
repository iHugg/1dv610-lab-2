<?php
namespace view;

class PostView {
  private static $post = "PostView::Post";
  private static $createPost = "PostView::CreatePost";
  private static $messageId = "PostView::Message";
  private $threadSQL;
  private $session;

  public function __construct(\mysqli $connection) {
    $this->threadSQL = new \model\ThreadSQL($connection);
    $this->session = new Session();
  }

  public function generatePostHTML(int $id) : string {
    $title = $this->threadSQL->getTitle($id);
    return '
    <h2>' . $title . '</h2>
    <form method="post">
      <fieldset>
        <legend>Enter your post</legend>
        <p id="' . self::$messageId . '">' . $this->session->getMessage() . '</p>
        <textarea id="' . self::$post . '" name="' . self::$post . '" rows="6" cols="50">' . $this->session->getPost() . '</textarea>
        <input type="submit" name="' . self::$createPost . '" value="Post" />
      </fieldset>
    </form>
    ';
  }

  public function wantsToPost() : bool {
    return isset($_POST[self::$createPost]);
  }

  public function getIdFromURL() : int {
    parse_str($_SERVER["QUERY_STRING"], $result);
    
    if (isset($result["id"])) {
      return $result["id"];
    }

    return -1;
  }

  public function getPost() : string {
    return $_POST[self::$post];
  }
}
?>
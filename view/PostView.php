<?php
namespace view;

class PostView {
  private static $post = "PostView::Post";
  private static $createPost = "PostView::CreatePost";

  public function generatePostHTML(string $title) : string {
    return '
    <h2>' . $title . '</h2>
    <form method="post">
      <fieldset>
        <legend>Enter your post</legend>
        <textarea id="' . self::$post . '" name="' . self::$post . '" rows="6" cols="50"></textarea>
        <input type="submit" name="' . self::$createPost . '" value="Post" />
      </fieldset>
    </form>
    ';
  }

  public function wantsToPost() : bool {
    return isset($_POST[self::$createPost]);
  }

  public function getTitle() : string {
    parse_str($_SERVER["QUERY_STRING"], $result);
    
    if (isset($result["title"])) {
      return $result["title"];
    }

    return "";
  }

  public function getPost() : string {
    return $_POST[self::$post];
  }
}
?>
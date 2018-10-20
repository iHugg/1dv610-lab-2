<?php
namespace view;

/**
 * Handles the html for the post related pages.
 */
class PostView extends BaseView {
  private static $post = "PostView::Post";
  private static $createPost = "PostView::CreatePost";
  private static $messageId = "PostView::Message";
  private static $deletePost = "PostView::DeletePost";
  private static $postIdName = "PostView::PostId";

  public function __construct(\mysqli $connection) {
    parent::__construct($connection);
  }

  /**
   * Generates the html code for the page where you create a post.
   * You're not allowed to create a post if you're not logged in.
   */
  public function generatePostHTML(int $id) : string {
    $thread = null;
    try {
      $thread = $this->threadSQL->getThread($id);
    } catch (\Exception $ex) {
      return '
      <h1>No such thread was found</h1>
      ';
    }

    return '
    <h2>' . $thread->getTitle() . '</h2>
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

  /**
   * Returns the html code containing all the posts for the specific thread.
   */
  public function getPosts(\model\Thread $thread) : string {
    $postHtml = "";

    if ($thread != null) {
      foreach ($thread->getPosts() as $key => $post) {
        $submit = $this->getDeletePostButtonHTML($post, $thread);

        $postHtml .= '
        <form method="post">
          <fieldset>
            <legend>' . $post->getAuthor() . '</legend>
            <p>' . nl2br($post->getPost()) . '</p>
            ' . $submit . '
          </fieldset>
        </form>
        ';
      }
    }

    return $postHtml;
  }

  /**
   * You're only allowed to delete a post if you're logged in and you created that post or you're
   * the creator of the thread or you're the admin.
   */
  private function getDeletePostButtonHTML(\model\Post $post, \model\Thread $thread) : string {
    $submit = '
    <input type="hidden" id="' . self::$postIdName . '" name="' . self::$postIdName . '" value="' . $post->getId() . '"/>
    <input type="submit" name="' . self::$deletePost . '" value="Delete post" style="float:right;">
    ';

    if ((!$this->session->isLoggedIn() || $this->session->getUsername() != $post->getAuthor()) && 
    $this->session->getUsername() != "Admin" && $this->session->getUsername() != $thread->getAuthor()) {
      $submit = "";
    }

    return $submit;
  }

  public function wantsToPost() : bool {
    return isset($_POST[self::$createPost]);
  }

  public function getIdFromURL() : int {
    return $_GET[self::$idQuery];
  }

  public function getPost() : string {
    return $_POST[self::$post];
  }

  public function getPostId() : int {
    return $_POST[self::$postIdName];
  }

  public function wantsToDeletePost() : bool {
    return isset($_POST[self::$deletePost]);
  }
}
?>
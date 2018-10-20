<?php
namespace view;

/**
 * Using this class to try and avoid printing to session from the controller.
 */
class SessionPrinter {
  private $session;
  private $loginView;
  private $registerView;

  public function __construct() {
    $this->session = new Session();
    $this->loginView = new LoginView();
    $this->registerView = new RegisterView();
  }

  public function loggedIn() {
    $this->session->setMessage("Welcome");
  }

  public function rememberLogin() {
    $this->session->setMessage("Welcome and you will be remembered");
  }

  public function loggedInWithCookies() {
    $this->session->setMessage("Welcome back with cookie");
  }

  public function usernameMissing() {
    $this->session->setMessage("Username is missing");
  }

  public function passwordMissing() {
    $this->session->setMessage("Password is missing");
  }

  public function wrongCredentials() {
    $this->session->setMessage("Wrong name or password");
  }

  public function logout() {
    $this->session->setMessage("Bye bye!");
  }

  public function userRegistered() {
    $this->session->setMessage("Registered new user.");
  }

  public function registerDatabaseError() {
    $this->session->setMessage("Something went wrong when registering the user.");
  }

  public function usernameTooShort(\model\User $user) {
    $this->session->addToMessage("Username has too few characters, at least " . $user->getUsernameMinLength() . " characters.");
  }

  public function passwordTooShort(\model\User $user) {
    $this->session->addToMessage("Password has too few characters, at least " . $user->getPasswordMinLength() . " characters.");
  }

  public function passwordsDontMatch() {
    $this->session->addToMessage("Passwords do not match.");
  }

  public function usernameAlreadyExists() {
    $this->session->addToMessage("User exists, pick another username.");
  }

  public function invalidCharacter() {
    $this->session->addToMessage("Username contains invalid characters.");
  }

  public function cookieError() {
    $this->session->setMessage("Wrong information in cookies");
  }

  public function emptyMessage() {
    $this->session->setMessage("");
  }

  public function setLoggedInUsername(\model\User $user) {
    $this->session->setUsername($user->getUsername());
  }

  public function setLoginEnteredUsername() {
    $enteredUsername = $this->loginView->getEnteredUsername();
    $this->session->setEnteredUsername($enteredUsername);
  }

  public function setRegisterEnteredUsername() {
    $enteredUsername = $this->registerView->getUsername();
    if (strlen($enteredUsername) > 0) {
      $enteredUsername = $this->removeTagsAndInvalidCharacters($enteredUsername);
    }
    $this->session->setEnteredUsername($enteredUsername);
  }

  public function titleIsTooShort($thread) {
    $this->session->setMessage("The title is too short, at least " . $thread->getTitleMinChar() . " characters are needed.");
  }

  public function titleIsTooLong($thread) {
    $this->session->setMessage("The title is too long, use a maximum of " . $thread->getTitleMaxChar() . " characters.");
  }

  public function titleContainsInvalidChar() {
    $this->session->setMessage("Title contains invalid character.");
  }

  public function setThreadTitle(\model\Thread $thread) {
    $this->session->setThreadTitle($thread->getTitle());
  }

  public function postIsEmpty() {
    $this->session->setMessage("Post is empty, please enter something.");
  }

  public function postContainsInvalidChar() {
    $this->session->setMessage("Post contains invalid character.");
  }

  public function setPost(\model\Post $post) {
    $this->session->setPost($post->getPost());
  }

  public function emptyTitle() {
    $this->session->setThreadTitle("");
  }

  public function emptyPost() {
    $this->session->setPost("");
  }

  public function emptyUsername() {
    $this->session->setUsername("");
  }

  /**
   * Probably just enough to remove tags.
   * Thought it might as well remove some non-commonly used characters as well.
   */
  public function removeTagsAndInvalidCharacters(string $data) : string {
    $data = strip_tags($data);
    return preg_replace('/[^A-Za-z0-9\-?!#$ ]/', '', $data);
  }
}
?>
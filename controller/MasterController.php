<?php
namespace controller;

/**
 * This class handles most of the high level calls,
 * passing along to expert controllers.
 */
class MasterController extends BaseController {
  private $loginController;
  private $logoutController;
  private $registerController;
  private $tamperingController;
  private $threadController;
  private $postController;
  private $database;
  private $tamperingFound;
  private $isLoggedIn;

  public function __construct() {
    $this->database = new \model\Database();
    $this->connection = $this->database->getConnection();
    parent::__construct($this->connection);
    $this->loginController = new LoginController($this->connection);
    $this->logoutController = new LogoutController($this->connection);
    $this->registerController = new RegisterController($this->connection);
    $this->tamperingController = new TamperingController($this->connection);
    $this->threadController = new ThreadController($this->connection);
    $this->postController = new PostController($this->connection);
    $this->tamperingFound = false;
    $this->isLoggedIn = $this->session->isLoggedIn();
  }

  public function start() {
    $this->handleCookieTampering();
    $this->handleSessionTheft();
    $this->handleAction();

    $this->layoutView->render($this->isLoggedIn, $this->tamperingFound);
    $this->handleFlashMessage();
  }

  /**
   * Checks if cookies have been tampered with.
   * If true then print error message, log the user out and remove the cookies.
   */
  private function handleCookieTampering() {
    if ($this->loginView->loginCookiesExist() && $this->tamperingController->hasCookieBeenTamperedWith()) {
      $this->sessionPrinter->cookieError();
      $this->tamperingFound = true;
      $this->session->logout();
      $this->loginView->removeLoginCookies();
    }
  }

  /**
   * Checks if the session has been stolen.
   * If true logout the thieving user.
   */
  private function handleSessionTheft() {
    if ($this->tamperingController->hasSessionBeenStolen()) {
      $this->tamperingFound = true;
      $this->isLoggedIn = false;
    }
  }

  /**
   * This method can probably be done in a nicer way.
   * It checks which action the user wants to do.
   * Depending on the action it sends it off to methods handling that action.
   */
  private function handleAction() {
    if ($this->loginView->wantsToLogin() && !$this->session->isLoggedIn()) {
      $this->loginController->handleLogin();
    } else if ($this->loginView->wantsToLogout() && $this->session->isLoggedIn()) {
      $this->logoutController->handleLogout();
    } else if ($this->registerView->wantsToRegister()) {
      $this->registerController->handleRegister();
    } else if ($this->loginView->loginCookiesExist() && !$this->session->isLoggedIn()) {
      $this->loginController->handleLoginByCookies();
      $this->isLoggedIn = $this->session->isLoggedIn();
    } else if ($this->threadView->wantsToCreateThread()) {
      $this->threadController->createThread();
    } else if ($this->postView->wantsToPost()) {
      $this->postController->savePost();
    } else if ($this->postView->wantsToDeletePost()) {
      $this->postController->deletePost();
    } else if ($this->threadView->wantsToDeleteThread()) {
      $this->threadController->deleteThread();
    }
  }

  /**
   * Removes the flash message if no action was taken.
   * E.g. on a page reload.
   */
  private function handleFlashMessage() {
    if (!$this->loginView->wantsToLogin() &&
    !$this->loginView->wantsToLogout() &&
    !$this->registerView->wantsToRegister() &&
    !$this->threadView->wantsToCreateThread() &&
    !$this->postView->wantsToPost() &&
    !$this->postView->wantsToDeletePost() &&
    !$this->threadView->wantsToDeleteThread()) {
      $this->sessionPrinter->emptyMessage();
    }
  }
}
?>
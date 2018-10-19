<?php
namespace controller;

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

  private function handleCookieTampering() {
    if ($this->loginView->loginCookiesExist() && $this->tamperingController->hasCookieBeenTamperedWith()) {
      $this->sessionPrinter->cookieError();
      $this->tamperingFound = true;
      $this->session->logout();
      $this->loginView->removeLoginCookies();
    }
  }

  private function handleSessionTheft() {
    if ($this->tamperingController->hasSessionBeenStolen()) {
      $this->tamperingFound = true;
      $this->isLoggedIn = false;
    }
  }

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
    } else if ($this->threadView->wantsToDeletePost()) {
      $this->postController->deletePost();
    } else if ($this->threadView->wantsToDeleteThread()) {
      $this->threadController->deleteThread();
    }
  }

  private function handleFlashMessage() {
    if (!$this->loginView->wantsToLogin() &&
    !$this->loginView->wantsToLogout() &&
    !$this->registerView->wantsToRegister() &&
    !$this->threadView->wantsToCreateThread() &&
    !$this->postView->wantsToPost() &&
    !$this->threadView->wantsToDeletePost()) {
      $this->sessionPrinter->emptyMessage();
    }
  }
}
?>
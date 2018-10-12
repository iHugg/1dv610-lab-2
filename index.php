<?php
session_start();
//INCLUDE THE FILES NEEDED...
require_once('view/LoginView.php');
require_once('view/DateTimeView.php');
require_once('view/LayoutView.php');
require_once('view/RegisterView.php');
require_once('controller/LoginController.php');
require_once('controller/LogoutController.php');
require_once('controller/RegisterController.php');
require_once('controller/TamperingController.php');
require_once('model/Session.php');
require_once('model/Database.php');
require_once('model/BrowserDatabase.php');
require_once('model/User.php');

//MAKE SURE ERRORS ARE SHOWN... MIGHT WANT TO TURN THIS OFF ON A PUBLIC SERVER
error_reporting(E_ALL);
ini_set('display_errors', 'On');

//CREATE OBJECTS OF THE VIEWS
$loginView = new \view\LoginView();
$registerView = new \view\RegisterView();
$layoutView = new \view\LayoutView($loginView, $registerView);
$loginController = new \controller\LoginController($loginView, $layoutView);
$logoutController = new \controller\LogoutController($loginView, $layoutView);
$registerController = new \controller\RegisterController($registerView, $layoutView);
$tamperingController = new \controller\TamperingController($layoutView, $loginView);
$session = new \model\Session();
$tamperingFound = false;
$isLoggedIn = $session->isLoggedIn();

if (!$session->isBrowserSet()) {
  $session->setBrowserName($layoutView->getBrowser());
}

if ($loginView->loginCookiesExist() && $tamperingController->hasCookieBeenTamperedWith()) {
  $session->setMessage("Wrong information in cookies");
  $tamperingFound = true;
  $session->setLoggedIn(false);
  $loginView->removeLoginCookies();
} else if ($tamperingController->hasSessionBeenStolen()) {
  $tamperingFound = true;
  $isLoggedIn = false;
}

if ($loginView->wantsToLogin() && !$session->isLoggedIn()) {
  $loginController->handleLogin();
} else if ($loginView->wantsToLogout() && $session->isLoggedIn()) {
  $logoutController->handleLogout();
} else if ($registerView->wantsToRegister()) {
  $registerController->handleRegister();
} 
else if ($loginView->loginCookiesExist() && !$session->isLoggedIn()) {
  $loginController->handleLoginByCookies();
  $isLoggedIn = $session->isLoggedIn();
}
$layoutView->render($isLoggedIn, $tamperingFound);

if (!$loginView->wantsToLogin() && !$loginView->wantsToLogout() && !$registerView->wantsToRegister()) {
  $session->setMessage("");
}

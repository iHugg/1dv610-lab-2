<?php
session_start();
//INCLUDE THE FILES NEEDED...
require_once('view/LoginView.php');
require_once('view/DateTimeView.php');
require_once('view/LayoutView.php');
require_once('controller/LoginController.php');
require_once('controller/LogoutController.php');
require_once('model/Session.php');
require_once('model/Database.php');
require_once('model/User.php');

//MAKE SURE ERRORS ARE SHOWN... MIGHT WANT TO TURN THIS OFF ON A PUBLIC SERVER
error_reporting(E_ALL);
ini_set('display_errors', 'On');

//CREATE OBJECTS OF THE VIEWS
$loginView = new \view\LoginView();
$dateTimeView = new \view\DateTimeView();
$layoutView = new \view\LayoutView();
$loginController = new \controller\LoginController($loginView, $layoutView);
$logoutController = new \controller\LogoutController($loginView, $layoutView);
$session = new \model\Session();

if ($loginView->wantsToLogin()) {
  $loginController->handleLogin();
} else if ($loginView->wantsToLogout()) {
  $logoutController->handleLogout();
}
$layoutView->render($session->getLoggedIn(), $loginView, $dateTimeView);

if (!$loginView->wantsToLogin() && !$loginView->wantsToLogout()) {
  $session->setMessage("");
}

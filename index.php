<?php
session_start();
//INCLUDE THE FILES NEEDED...
require_once('view/LoginView.php');
require_once('view/DateTimeView.php');
require_once('view/LayoutView.php');
require_once('controller/LoginController.php');
require_once('model/Session.php');

//MAKE SURE ERRORS ARE SHOWN... MIGHT WANT TO TURN THIS OFF ON A PUBLIC SERVER
error_reporting(E_ALL);
ini_set('display_errors', 'On');

//CREATE OBJECTS OF THE VIEWS
$loginView = new \view\LoginView();
$dateTimeView = new \view\DateTimeView();
$layoutView = new \view\LayoutView();
$loginController = new \controller\LoginController($loginView, $layoutView, $dateTimeView);
$session = new \model\Session();

if ($loginView->wantsToLogin()) {
  $loginController->checkEmptyLoginFields();
  $loginControler->checkLoginCredentials();

  $layoutView->redirectToLoginPage();
}
$layoutView->render(false, $loginView, $dateTimeView);

if (!$loginView->wantsToLogin()) {
  $session->setMessage("");
}

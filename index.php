<?php
session_start();
//INCLUDE THE FILES NEEDED...
require_once('view/LoginView.php');
require_once('view/DateTimeView.php');
require_once('view/LayoutView.php');
require_once('controller/LoginController.php');
require_once('controller/LogoutController.php');
require_once('controller/RegisterController.php');
require_once('lib/Logic.php');

//MAKE SURE ERRORS ARE SHOWN... MIGHT WANT TO TURN THIS OFF ON A PUBLIC SERVER
error_reporting(E_ALL);
ini_set('display_errors', 'Off');

if (!isset($_SESSION["flash"])) {
  Logic::Init_Session();
}

//CREATE OBJECTS OF THE VIEWS
$loginView = new LoginView();
$dateTimeView = new DateTimeView();
$layoutView = new LayoutView();
$loginControl = new LoginController();
$logoutControl = new LogoutController();
$registerController = new RegisterController();
$con = new mysqli("den1.mysql3.gear.host", "lab2db1dv610", "Xo24?fI0Ppy_", "lab2db1dv610");
$errorFound = false;

//Checks if session has been stolen
if (!isset($_SESSION["browser"])) {
  $_SESSION["browser"] = $_SERVER["HTTP_USER_AGENT"];
} else if ($_SESSION["browser"] != $_SERVER["HTTP_USER_AGENT"]) {
  $errorFound = true;
  $layoutView->render(false, $loginView, $dateTimeView, "", false);
}

if ($con->connect_error) {
  die("Connection failed: " . $con->connect_error);
}

//Checks if password cookie has been tampered with
if (!Logic::CheckCookieTampering($con) && isset($_COOKIE["LoginView::CookiePassword"])) {
  $errorFound = true;
  $layoutView->render(false, $loginView, $dateTimeView, "Wrong information in cookies", false);
}

//If no errors were found, print the page depending on the action taken
if (!$errorFound) {
  if ($loginControl->WantToLogin()) {
    $loginControl->CheckLoginCredentials($con);
  } else if ($logoutControl->WantToLogout()) {
    $logoutControl->Logout();
  } else if (isset($_COOKIE["LoginView::CookieName"]) && isset($_COOKIE["LoginView::CookiePassword"]) && !$_SESSION["loggedIn"]) {
    $loginControl->LoginWithCookies($_COOKIE["LoginView::CookieName"], $_COOKIE["LoginView::CookiePassword"]);
    $layoutView->render($_SESSION["loggedIn"], $loginView, $dateTimeView, $_SESSION["flash"], isset($_GET["register"]));
    $_SESSION["flash"] = "";
  } else if (isset($_POST["RegisterView::Register"])) {
    $registerController->CheckRegisterCredentials($con);
  } else {
    $layoutView->render($_SESSION["loggedIn"], $loginView, $dateTimeView, $_SESSION["flash"], isset($_GET["register"]));
    $_SESSION["flash"] = "";
  }
}
$con->close();

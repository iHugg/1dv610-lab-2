<?php

//INCLUDE THE FILES NEEDED...
require_once('view/LoginView.php');
require_once('view/DateTimeView.php');
require_once('view/LayoutView.php');
require_once('controller/LoginController.php');
require_once('controller/LogoutController.php');
require_once('lib/Logic.php');

//MAKE SURE ERRORS ARE SHOWN... MIGHT WANT TO TURN THIS OFF ON A PUBLIC SERVER
error_reporting(E_ALL);
ini_set('display_errors', 'Off');
session_start();
if ($_SESSION["flash"] == null) {
  Logic::Init_Session();
}

//CREATE OBJECTS OF THE VIEWS
$loginView = new LoginView();
$dateTimeView = new DateTimeView();
$layoutView = new LayoutView();
$loginControl = new LoginController();
$logoutControl = new LogoutController();
// $con = mysqli_connect("localhost", "root", "", "1dv610_lab_2_db");

/*if ($con->connect_error) {
  die("Connection failed: " . $con->connect_error);
}

$sql = "SELECT id, username, password FROM users";
$result = $con->query($sql);

if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    echo "id: " . $row["id"] . " - Username: " . $row["username"] . " - Password: " . $row["password"];
  }
} else {
  echo "0 results.";
}
*/
$message = "";

if ($_SESSION["flash"] != null) {
  $message = $_SESSION["flash"];
}

if ($loginControl->WantToLogin()) {
  //$_SESSION["wantsToLogin"] = true;
  $loginControl->CheckLoginCredentials();
} else if ($logoutControl->WantToLogout()) {
  $logoutControl->Logout();
} else {
  $layoutView->render($_SESSION["loggedIn"], $loginView, $dateTimeView, $message);
}
//$con->close();

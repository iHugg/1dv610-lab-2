<?php

//INCLUDE THE FILES NEEDED...
require_once('view/LoginView.php');
require_once('view/DateTimeView.php');
require_once('view/LayoutView.php');
require_once('controller/LoginController.php');

//MAKE SURE ERRORS ARE SHOWN... MIGHT WANT TO TURN THIS OFF ON A PUBLIC SERVER
error_reporting(E_ALL);
ini_set('display_errors', 'On');
session_start();

//CREATE OBJECTS OF THE VIEWS
$loginView = new LoginView();
$dateTimeView = new DateTimeView();
$layoutView = new LayoutView();
$control = new LoginController();
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
if ($control->WantToLogin()) {
  $control->CheckLoginCredentials($con);
} else {
  $control->Login($layoutView, $loginView, $dateTimeView);
  //$layoutView->render(false, $loginView, $dateTimeView, "");
}
$con->close();

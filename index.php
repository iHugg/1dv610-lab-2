<?php
session_start();
//INCLUDE THE FILES NEEDED...
require_once('view/BaseView.php');
require_once('view/LoginView.php');
require_once('view/DateTimeView.php');
require_once('view/LayoutView.php');
require_once('view/RegisterView.php');
require_once('view/Session.php');
require_once('view/SessionPrinter.php');
require_once('view/ThreadView.php');
require_once('view/PostView.php');
require_once('controller/BaseController.php');
require_once('controller/LoginController.php');
require_once('controller/LogoutController.php');
require_once('controller/RegisterController.php');
require_once('controller/TamperingController.php');
require_once('controller/MasterController.php');
require_once('controller/ThreadController.php');
require_once('controller/PostController.php');
require_once('model/Database.php');
require_once('model/UserSQL.php');
require_once('model/BrowserSQL.php');
require_once('model/ThreadSQL.php');
require_once('model/User.php');
require_once('model/Thread.php');
require_once('model/Post.php');

//MAKE SURE ERRORS ARE SHOWN... MIGHT WANT TO TURN THIS OFF ON A PUBLIC SERVER
error_reporting(E_ALL);
ini_set('display_errors', 'On');

$masterController = new \controller\MasterController();
$masterController->start();

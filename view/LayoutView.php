<?php


class LayoutView {
  
  public function render($isLoggedIn, $v, $dtv, $message, $goToRegister) {
    $location = "";
    $query = "";
    $aTagMessage = "Back to login";

    if ($_SERVER["HTTP_HOST"] == "localhost") {
      $location = "/1dv610-lab-2";
    }

    if (!$goToRegister) {
      $query = "?register";
      $aTagMessage = "Register a new user";
    }

    echo '<!DOCTYPE html>
      <html>
        <head>
          <meta charset="utf-8">
          <title>Login Example</title>
        </head>
        <body>
          <h1>Assignment 2</h1>
          <a href="' . $location . '/index.php' . $query . '" id="register">' . $aTagMessage . '</a>
          ' . $this->renderIsLoggedIn($isLoggedIn) . '
          
          <div class="container">
              ' . $v->response($isLoggedIn, $message, $goToRegister) . '
              
              ' . $dtv->show() . '
          </div>
         </body>
      </html>
    ';
  }

  private function renderIsLoggedIn($isLoggedIn) {
    if ($isLoggedIn) {
      return '<h2>Logged in</h2>';
    }
    else {
      return '<h2>Not logged in</h2>';
    }
  }
}

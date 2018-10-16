<?php
namespace view;

class LoginView {
	private static $login = 'LoginView::Login';
	private static $logout = 'LoginView::Logout';
	private static $name = 'LoginView::UserName';
	private static $password = 'LoginView::Password';
	private static $cookieName = 'LoginView::CookieName';
	private static $cookiePassword = 'LoginView::CookiePassword';
	private static $keep = 'LoginView::KeepMeLoggedIn';
  private static $messageId = 'LoginView::Message';
  private $session;

  public function __construct() {
    $this->session = new Session();
  }

	/**
	 * Create HTTP response
	 *
	 * Should be called after a login attempt has been determined
	 *
	 * @return  void BUT writes to standard output and cookies!
	 */
	public function response(bool $isLoggedIn) : string {
    $response = "";
    $message = $this->session->getMessage();

    if (!$isLoggedIn) {
      $response = $this->generateLoginFormHTML($message);
    } else {
      $response .= $this->generateLogoutButtonHTML($message);
    }
		return $response;
	}

	/**
	* Generate HTML code on the output buffer for the logout button
	* @param $message, String output message
	* @return  void, BUT writes to standard output!
	*/
	private function generateLogoutButtonHTML(string $message) : string {
		return '
			<form  method="post" >
				<p id="' . self::$messageId . '">' . $message .'</p>
				<input type="submit" name="' . self::$logout . '" value="logout"/>
			</form>
		';
	}
	
	/**
	* Generate HTML code on the output buffer for the logout button
	* @param $message, String output message
	* @return  void, BUT writes to standard output!
	*/
	private function generateLoginFormHTML(string $message) : string {
		return '
			<form method="post" > 
				<fieldset>
					<legend>Login - enter Username and password</legend>
					<p id="' . self::$messageId . '">' . $message . '</p>
					
					<label for="' . self::$name . '">Username :</label>
					<input type="text" id="' . self::$name . '" name="' . self::$name . '" value="' . $this->session->getEnteredUsername() . '" />

					<label for="' . self::$password . '">Password :</label>
					<input type="password" id="' . self::$password . '" name="' . self::$password . '" />

					<label for="' . self::$keep . '">Keep me logged in  :</label>
					<input type="checkbox" id="' . self::$keep . '" name="' . self::$keep . '" />
					
					<input type="submit" name="' . self::$login . '" value="login" />
				</fieldset>
			</form>
		';
	}
  
  public function wantsToLogin() : bool {
    return isset($_POST[self::$login]);
  }

  public function wantsToStayLoggedIn() : bool {
    return isset($_POST[self::$keep]);
  }

  public function getEnteredUsername() : string {
    return $_POST[self::$name];
  }

  public function isUsernameEmpty() : bool {
    return $_POST[self::$name] == "";
  }

  public function getEnteredPassword() : string {
    return $_POST[self::$password];
  }

  public function isPasswordEmpty() : bool {
    return $_POST[self::$password] == "";
  }

  public function wantsToLogout() : bool {
    return isset($_POST[self::$logout]);
  }

  public function setLoginCookies(string $username, string $hashedPassword) {
    setcookie(self::$cookieName, $username, time() + (24 * (60 + 60)));
    setcookie(self::$cookiePassword, $hashedPassword, time() + (24 * (60 + 60)));
  }

  public function loginCookiesExist() : bool {
    return (isset($_COOKIE[self::$cookieName]) && isset($_COOKIE[self::$cookiePassword]));
  }

  public function getCookieUser() : \model\User {
    return new \model\User($_COOKIE[self::$cookieName], $_COOKIE[self::$cookiePassword]);
  }

  public function removeLoginCookies() {
    if (isset($_COOKIE[self::$cookieName])) {
      setcookie(self::$cookieName, "", time() - 3600);
    }

    if (isset($_COOKIE[self::$cookiePassword])) {
      setcookie(self::$cookiePassword, "", time() - 3600);
    }
  }
}
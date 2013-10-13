<?php

namespace view;

require_once("model/Login.php");

class Login {
	/**
	 * @var string
	 */
	private static $usernameDataKey = "username";

	/**
	 * @var string
	 */
	private static $passwordDataKey = "password";

	/**
	 * @var string
	 */
	private static $cookieEndTimeFile = "cookie_endtime.txt";

	/**
	 * @var string
	 */
	private static $usernameCookie = "LoginView::Username";

	/**
	 * @var string
	 */
	private static $passwordCookie = "LoginView::Password";

	/**
	 * @var string
	 */
	private static $logout = "logout";

	/**
	 * @var string
	 */
	private static $formSubmit = "submit";

	/**
	 * @var string
	 */
	private static $formRememberMe = "remember_me";

	/**
	 * Returns a username from either POST data or cookie
	 * @return string username
	 */
	public function getUsername() {
		//1. check POST
		if(isset($_POST[self::$usernameDataKey]))
			return $_POST[self::$usernameDataKey];

		//2. check cookie
		if(isset($_COOKIE[self::$usernameCookie]))
			return $_COOKIE[self::$usernameCookie];
	}

	/**
	 * Returns a password from either POST data or cookie
	 * @return string password
	 */
	public function getPassword() {
		//1. check POST
		if(isset($_POST[self::$passwordDataKey]) && !empty($_POST[self::$passwordDataKey]))
			return md5($_POST[self::$passwordDataKey]);

		//2. check cookie
		if(isset($_COOKIE[self::$passwordCookie]))
			return $_COOKIE[self::$passwordCookie];
	}

	/**
	 * Checks if user is logging out
	 * @return boolean
	 */
	public function isUserLoggingOut() {
		return isset($_GET[self::$logout]);
	}

	/**
	 * Checks if user submitted a login form
	 * @return boolean
	 */
	public function isFormSubmit() {
		return isset($_POST[self::$formSubmit]);
	}

	/**
	 * Checks if user has cookies with login data set
	 * @return boolean
	 */
	public function userHasCookieData() {
		return (isset($_COOKIE[self::$usernameCookie]) && isset($_COOKIE[self::$passwordCookie]));
	}

	public function validateCookieData() {
		return (time() < file_get_contents(self::$cookieEndTimeFile));
	}

	/**
	 * Saves login data in users cookies
	 * @return void
	 */
	public function saveCookieData() {
		$expires = time() + 10;

		//Write expected end time for cookie to expire to file
		file_put_contents(self::$cookieEndTimeFile, $expires);

		//Add cookies
		setcookie(self::$usernameCookie, $this->getUsername(), $expires);
		setcookie(self::$passwordCookie, $this->getPassword(), $expires);
	}

	/**
	 * Checks if user wants to save credentials in cookies
	 * @return boolean
	 */
	public function userPersistentLogin() {
		return isset($_POST[self::$formRememberMe]);
	}

	/**
	 * Checks if user has cookies and if they do, removes it
	 * @return void
	 */
	public function removeCookieData() {
		if(isset($_COOKIE[self::$usernameCookie]))
			setcookie(self::$usernameCookie, "", time() - 3600);

		if(isset($_COOKIE[self::$passwordCookie]))
			setcookie(self::$passwordCookie, "", time() - 3600);
	}

	/**
	 * Returns HTML for a logged in user
	 * @param  string $message
	 * @return string
	 */
	public function getLoggedIn($message) {
		$loginModel = new \model\Login();
		$username = $loginModel->getLoggedInUser();

		return '
			<h2>' . $username . ' är inloggad</h2>
			' . (isset($message) ? '<p>' . $message . '</p>' : '') . '
			<a href="index.php?' . self::$logout . '">Logga ut</a>
		';
	}
	
	/**
	 * Returns HTML for a user who isn't logged in
	 * @param  string $message
	 * @return string
	 */
	public function getNotLoggedIn($message) {
		return '
			<h2>Ej Inloggad</h2>
			<form method="post" action="index.php" name="loginform">
				<fieldset>
					<legend>Login - Skriv in användarnamn och lösenord</legend>
					
					' . (isset($message) ? '<p>' . $message . '</p>' : '') . '
					
					<label for="username">Namn: </label>
					<input id="username" name="' . self::$usernameDataKey . '" type="text" 
					value="' . (isset($_POST[self::$usernameDataKey]) ? $_POST[self::$usernameDataKey] : '') . '">
					
					<label for="password">Lösenord: </label>
					<input id="password" name="' . self::$passwordDataKey . '" type="password">
					
					<label for="remember_me">Håll mig inloggad: </label>
					<input id="remember_me" name="' . self::$formRememberMe . '" type="checkbox">
					
					<input id="submit" name="' . self::$formSubmit . '" type="submit" value="Logga in">
				</fieldset>
			</form>
		';
	}
}
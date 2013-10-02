<?php

namespace view;

class Login {
	/**
	 * @var string
	 */
	private static $HTTP_USER_AGENT = "HTTP_USER_AGENT";

	/**
	 * @var string
	 */
	private static $isLoggedInSessionKey = "Login::is_logged_in";

	/**
	 * @var string
	 */
	private static $usernameSessionKey = "Login::Username";

	/**
	 * @var string
	 */
	private static $passwordSessionKey = "Login::Password";

	/**
	 * @var string
	 */
	private static $userAgentSessionKey = "Login::Browser";

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
	 * Returns a username POST
	 * @return string username
	 */
	public function getUsername() {
		//1. check POST
		if(isset($_POST[self::$usernameDataKey]))
			return $_POST[self::$usernameDataKey];
	}

	/**
	 * Returns a password from POST
	 * @return string password
	 */
	public function getPassword() {
		//1. check POST
		if(isset($_POST[self::$passwordDataKey]))
			return $_POST[self::$passwordDataKey];
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
	 * Checks if user has session data
	 * @return boolean
	 */
	public function userHasSessionData() {
		return (isset($_SESSION[self::$usernameSessionKey]) && 
			isset($_SESSION[self::$isLoggedInSessionKey]));
	}

	/**
	 * Validates a session to try and prevent session hijack
	 * @return boolean
	 */
	public function validateSessionData() {
		return ($_SESSION[self::$userAgentSessionKey] == $_SERVER[self::$HTTP_USER_AGENT]);
	}

	/**
	 * Saves session data when user is logging in
	 * @return void
	 */
	public function setSessionData() {
		$_SESSION[self::$usernameSessionKey] = $this->getUsername();
		$_SESSION[self::$passwordSessionKey] = $this->getPassword();
		$_SESSION[self::$userAgentSessionKey] = $_SERVER[self::$HTTP_USER_AGENT];
		$_SESSION[self::$isLoggedInSessionKey] = true;
	}

	/**
	 * Deletes session data when a user logs out
	 * @return void
	 */
	public function deleteSessionData() {
		$_SESSION = array();
		session_destroy();
	}

	/**
	 * Checks if the user is logged in
	 * @return boolean
	 */
	public function isLoggedIn() {
		return isset($_SESSION[self::$isLoggedInSessionKey]);
	}

	/**
	 * Returns HTML for a logged in user
	 * @param  string $message
	 * @return string
	 */
	public function getLoggedIn($message) {
		return '
			<h2>' . $_SESSION[self::$usernameSessionKey] . ' är inloggad</h2>
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
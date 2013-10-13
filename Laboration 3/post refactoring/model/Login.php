<?php

namespace model;

class Login {
	/**
	 * @var string
	 */
	private static $HTTP_USER_AGENT = "HTTP_USER_AGENT";

	/**
	 * @var string
	 */
	private static $sessionUsername = "LoginModel::Username";
	
	/**
	 * @var string
	 */
	private static $sessionPassword = "LoginModel::Password";

	/**
	 * @var string
	 */
	private static $sessionUserAgent = "LoginModel::UserAgent";

	/**
	 * @var string
	 */
	private static $sessionIsLoggedIn = "LoginModel::IsLoggedIn";

	/**
	 * Logs out the user
	 * @return void
	 */
	public function logout() {
		$_SESSION = array();
		session_destroy();
	}

	/**
	 * Login a user by starting a session and setting appropiate values
	 * @param  string $username
	 * @param  string $password
	 * @param  string $userAgent
	 * @return void
	 */
	public function loginUser($username, $password) {
		//Save the username.
		$_SESSION[self::$sessionUsername] = $username;

		//Save the password.
		$_SESSION[self::$sessionPassword] = $password;

		//Save the userAgent.
		$_SESSION[self::$sessionUserAgent] = $_SERVER[self::$HTTP_USER_AGENT];

		//Set the user as logged in.
		$_SESSION[self::$sessionIsLoggedIn] = true;
	}

	/**
	 * Checks if user is logged in
	 * @return boolean
	 */
	public function isUserLoggedIn() {
		return (isset($_SESSION[self::$sessionUsername])
			&& isset($_SESSION[self::$sessionIsLoggedIn])
			&& isset($_SESSION[self::$sessionUserAgent])
			&& $_SESSION[self::$sessionUserAgent] == $_SERVER[self::$HTTP_USER_AGENT]);
	}

	/**
	 * Returns current logged in user
	 * @return string
	 */
	public function getLoggedInUser() {
		return $_SESSION[self::$sessionUsername];
	}
}
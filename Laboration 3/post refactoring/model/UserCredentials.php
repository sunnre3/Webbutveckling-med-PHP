<?php

namespace model;

class UserCredentials {
	/**
	 * Hard coded username
	 * @var string
	 */
	private static $validUsername = "Admin";

	/**
	 * Hard coded password
	 * @var string
	 */
	private static $validPassword = "Password";

	/**
	 * Validate a username
	 * @param  string $username
	 * @return boolean
	 */
	public function validateUsername($username) {
		return $username == self::$validUsername;
	}

	/**
	 * Validate a password
	 * @param  string $password
	 * @return boolean
	 */
	public function validatePassword($password) {
		return $password == md5(self::$validPassword);
	}
}
<?php

namespace controller;

require_once("view/Login.php");

class Login {
	/**
	 * Boolean; is the current user logged in or not
	 * @var boolean
	 */
	private $is_logged_in = false;

	/**
	 * Hard coded username
	 * @var string
	 */
	private $validUsername = "Admin";

	/**
	 * Hard coded password
	 * @var string
	 */
	private $validPassword = "Password";

	/**
	 * @var \view\Login
	 */
	private $loginView;

	/**
	 * Username fetches from view
	 * @var string
	 */
	private $username = "";

	/**
	 * Password fetches from view
	 * @var string
	 */
	private $password = "";
	
	/**
	 * Used to convery input errors to view
	 * @var string
	 */
	public $message = "";
	
	/**
	 * Login/logout depending on state
	 */
	public function __construct() {
		//Start a new session
		session_start();

		//Initiate a loginView
		$this->loginView = new \view\Login();

		//Get username
		$this->username = $this->loginView->getUsername();

		//Get password
		$this->password = $this->loginView->getPassword();
		
		//First check if user is logging out
		if($this->loginView->isUserLoggingOut()) {
			$this->logout();
		}
		
		//Then if a user is atempting to log in with form data
		else if($this->loginView->isFormSubmit()) {
			$this->loginWithPostData();
		}
		
		//Thirdly if user is logging in with session data
		else if($this->loginView->userHasSessionData()) {
			$this->loginWithSessionData();
		}

		//Lastly if user is logging in with saved cookies
		else if($this->loginView->userHasCookieData()) {
			$this->loginWithCookieData();
		}
	}

	/**
	 * Validates a given password to the hard coded one
	 * @param  string $password
	 * @return boolean
	 */
	private function validatePassword($password) {
		return $password == md5($this->validPassword);
	}

	/**
	 * Validates a given username to the hard coded one
	 * @param  string $username
	 * @return boolean
	 */
	private function validateUsername($username) {
		return ($username == $this->validUsername);
	}

	/**
	 * Validates both username/password
	 * @return boolean
	 */
	private function validateLogin() {
		if($this->validateUsername($this->username) && $this->validatePassword($this->password))
			return true;

		return false;
	}
	
	/**
	 * Shows a message, resets the session, removes any saved data in cookies and logs out the user
	 * @return void
	 */
	private function logout() {
		if($this->loginView->isLoggedIn())
			$this->message = "Du har nu loggat ut";

		$this->loginView->removeCookieData();
		$this->loginView->deleteSessionData();
		
		$this->is_logged_in = false;
	}
	
	/**
	 * Login function used with POST
	 * @return void
	 */
	private function loginWithPostData() {

		if(empty($this->username)) {
			$this->message = "Användarnamn saknas";
			return;
		}
		
		else if(empty($this->password)) {
			$this->message = "Lösenord saknas";
			return;
		}
		
		else if(!$this->validateLogin()) {
			$this->message = "Felaktigt användarnamn och/eller lösenord";
			return;
		}
		
		$this->loginView->setSessionData();
		$this->is_logged_in = true;

		if($this->loginView->userPersistentLogin()) {
			$this->loginView->saveCookieData();
			$this->message = "Inloggning lyckades och vi kommer ihåg dig nästa gång";
		}

		else {
			$this->message = "Inloggning lyckades";
		}
	}
	
	/**
	 * Login function used with session
	 * @return void
	 */
	private function loginWithSessionData() {
		if($this->loginView->validateSessionData())
			$this->is_logged_in = true;
	}
	
	/**
	 * Login function with with saved cookies
	 * @return void
	 */
	private function loginWithCookieData() {
		if(!$this->validateLogin() || !$this->loginView->validateCookieData()) {
			$this->message = "Felaktig information i cookie";
			$this->loginView->removeCookieData();
			return;
		}

		$this->loginView->setSessionData();
		$this->message = "Inloggning lyckades via cookies";
		$this->is_logged_in = true;
	}

	/**
	 * Public function to check if the user is logged in
	 * @return void
	 */
	public function isLoggedIn() {
		return $this->is_logged_in;
	}
}
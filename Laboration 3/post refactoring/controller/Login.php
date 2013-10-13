<?php

namespace controller;

require_once("model/Login.php");
require_once("model/UserCredentials.php");
require_once("view/Login.php");

class Login {
	/**
	 * @var \view\Login
	 */
	private $loginView;

	/**
	 * @var \model\Login
	 */
	private $loginModel;

	/**
	 * @var \model\UserCredentials
	 */
	private $userCredentials;

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
	 * Used to convey input errors to view
	 * @var string
	 */
	public $message = "";
	
	/**
	 * Login/logout depending on state
	 */
	public function __construct() {
		//Initiate loginView
		$this->loginView = new \view\Login();

		//Initiate loginModel
		$this->loginModel = new \model\Login();

		//Initiate UserCredentials
		$this->userCredentials = new \model\UserCredentials();

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

		//Lastly if user is logging in with saved cookies
		else if($this->loginView->userHasCookieData() && !$this->loginModel->isUserLoggedIn()) {
			$this->loginWithCookieData();
		}
	}
	
	/**
	 * Shows a message, resets the session, removes any saved data in cookies and logs out the user
	 * @return void
	 */
	private function logout() {
		if($this->loginModel->isUserLoggedIn())
			$this->message = "Du har nu loggat ut";

		//Logout
		$this->loginModel->logout();

		//Remove cookies if there are any
		$this->loginView->removeCookieData();
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
		
		else if(!$this->userCredentials->validateUsername($this->username)
			|| !$this->userCredentials->validatePassword($this->password)) {
			$this->message = "Felaktigt användarnamn och/eller lösenord";
			return;
		}
		
		//Login the user
		$this->loginModel->loginUser($this->username, $this->password);

		if($this->loginView->userPersistentLogin()) {
			$this->loginView->saveCookieData();
			$this->message = "Inloggning lyckades och vi kommer ihåg dig nästa gång";
		}

		else {
			$this->message = "Inloggning lyckades";
		}
	}
	
	/**
	 * Login function with saved cookies
	 * @return void
	 */
	private function loginWithCookieData() {
		if(!$this->userCredentials->validateUsername($this->username) || 
			!$this->userCredentials->validatePassword($this->password) ||
			!$this->loginView->validateCookieData()) {
			$this->message = "Felaktig information i cookie";
			$this->loginView->removeCookieData();
			return;
		}

		//Login.
		$this->loginModel->loginUser($this->username, $this->password);

		//Set the message.
		$this->message = "Inloggning lyckades via cookies";
	}

	/**
	 * Public function to check if the user is logged in
	 * @return void
	 */
	public function isLoggedIn() {
		return $this->loginModel->isUserLoggedIn();
	}
}
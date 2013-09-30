<?php

namespace controller;

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
	private $username = "Admin";

	/**
	 * Hard coded password
	 * @var string
	 */
	private $password = "Password";
	
	/**
	 * Used to convery input errors to view
	 * @var string
	 */
	public $message = "";
	
	/**
	 * Login/logout depending on state
	 */
	public function __construct() {
		session_start();
		
		if(isset($_GET["logout"])) {
			$this->logout();
		}
		
		else if(isset($_POST["submit"])) {
			$this->loginWithPostData();
		}
		
		else if(!empty($_SESSION["username"]) && ($_SESSION["logged_in"])) {
			$this->loginWithSessionData();
		}
	}
	
	/**
	 * Shows a message, resets the session and logouts the user
	 */
	private function logout() {
		if(isset($_SESSION["username"]))
			$this->message = "Du har nu loggat ut";

		$_SESSION = array();
		session_destroy();
		$this->is_logged_in = false;
	}
	
	/**
	 * Login function used with POST
	 */
	private function loginWithPostData() {
		if(empty($_POST["username"])) {
			$this->message = "Användarnamn saknas";
			return;
		}
		
		else if(empty($_POST["password"])) {
			$this->message = "Lösenord saknas";
			return;
		}
		
		if($_POST["username"] != $this->username || $_POST["password"] != $this->password) {
			$this->message = "Felaktigt användarnamn och/eller lösenord";
			return;
		}
		
		$_SESSION["username"]	= $_POST["username"];
		$_SESSION["password"]	= $_POST["password"];
		$_SESSION["logged_in"]	= true;
		
		$this->is_logged_in = true;
		
		$this->message = "Inloggning lyckades";
	}
	
	/**
	 * Login function used with session
	 */
	private function loginWithSessionData() {
		$this->is_logged_in = true;
	}
	
	/**
	 * Public function to check if the user is logged in
	 */
	public function isLoggedIn() {
		return $this->is_logged_in;
	}
}
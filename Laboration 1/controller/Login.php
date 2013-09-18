<?php

namespace controller;

class Login {
	private $is_logged_in = false;
	private $username = "Admin";
	private $password = "Password";
	
	public $feedback;
	
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
	
	private function logout() {
		if(isset($_SESSION["username"]))
			$this->feedback = "Du har nu loggat ut";

		$_SESSION = array();
		session_destroy();
		$this->is_logged_in = false;
	}
	
	private function loginWithPostData() {
		if(empty($_POST["username"])) {
			$this->feedback = "Användarnamn saknas";
			return;
		}
		
		else if(empty($_POST["password"])) {
			$this->feedback = "Lösenord saknas";
			return;
		}
		
		if($_POST["username"] != $this->username || $_POST["password"] != $this->password) {
			$this->feedback = "Felaktigt användarnamn och/eller lösenord";
			return;
		}
		
		$_SESSION["username"]	= $_POST["username"];
		$_SESSION["password"]	= $_POST["password"];
		$_SESSION["logged_in"]	= true;
		
		$this->is_logged_in = true;
		
		$this->feedback = "Inloggning lyckades";
	}
	
	private function loginWithSessionData() {
		$this->is_logged_in = true;
	}
	
	public function isLoggedIn() {
		return $this->is_logged_in;
	}
}
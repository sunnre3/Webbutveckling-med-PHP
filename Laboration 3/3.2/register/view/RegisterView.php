<?php

namespace register\view;

require_once("./register/model/RegisterObserver.php");
require_once("./login/model/UserCredentials.php");
require_once("./login/model/UserName.php");
require_once("./login/model/Password.php");
require_once("./common/Filter.php");

class RegisterView implements \register\model\RegisterObserver {
	private static $REGISTER = "register";
	private static $NEW_USER = "newUser";
	private static $USERNAME = "RegisterView::UserName";
	private static $PASSWORD = "RegisterView::Password";
	private static $VERIFIED_PASSWORD = "RegisterView::VerifyPassword";
	private static $SUBMIT = "RegisterView::Submit";
	private static $LOGIN_USERNAME = "LoginView::UserName";

	/**
	 * @var string
	 */
	private $message = "";

	/**
	 * @var boolean
	 */
	private $registrationOK = false;

	/**
	 * @return string HTML
	 */
	public function getRegisterBox() {
		$username = $this->getUserName();

		if(\Common\Filter::hasTags($username))
			$username = \Common\Filter::sanitizeString($username);

		$html = "
			<form action='?" . self::$REGISTER . "' method='post' enctype='multipart/form-data'>
				<fieldset>
					$this->message
					<legend>Registrera ny användare - Skriv in användarnamn och lösenord</legend>
					<p><label for='UserNameID' >Namn :</label>
					<input type='text' size='20' name='" . self::$USERNAME . "' id='UserNameID' value='$username' /></p>
					<p><label for='PasswordID' >Lösenord  :</label>
					<input type='password' size='20' name='" . self::$PASSWORD . "' id='PasswordID' value='' /></p>
					<p><label for='PasswordID' >Repetera Lösenord  :</label>
					<input type='password' size='20' name='" . self::$VERIFIED_PASSWORD . "' id='VerifyPasswordID' value='' /></p>
					<p><label for='" . self::$SUBMIT . "'>Skicka : </label><input type='submit' name='" . self::$SUBMIT . "'  value='Registrera' /></p>
				</fieldset>
			</form>";
			
		return $html;
	}

	/**
	 * @return string
	 */
	private function getUserName() {
		if(isset($_POST[self::$USERNAME]))
			return $_POST[self::$USERNAME];
		else
			return "";
	}

	/**
	 * @return string
	 */
	private function getPassword() {
		if(isset($_POST[self::$PASSWORD]))
			return $_POST[self::$PASSWORD];
		else
			return "";
	}

	private function getVerifiedPassword() {
		if(isset($_POST[self::$VERIFIED_PASSWORD]))
			return $_POST[self::$VERIFIED_PASSWORD];
		else
			return "";
	}

	/**
	 * @return \login\model\UserCreddentials
	 */
	public function getUserCredentials() {
		return \login\model\UserCredentials::create(new \login\model\UserName($this->getUserName()), 
													\login\model\Password::fromCleartext($this->getPassword()));
	}

	/**
	 * From RegisterObserver
	 * @return void
	 */
	public function registrationFailed() {
		$userNameMinLength = \login\model\UserName::MINIMUM_USERNAME_LENGTH;
		$userNameMaxLength = \login\model\UserName::MAXIMUM_USERNAME_LENGTH;
		$passWordMinLength = \login\model\Password::MINIMUM_PASSWORD_CHARACTERS;
		$passWordMaxLength = \login\model\Password::MAXIMUM_PASSWORD_CHARACTERS;

		$badInput = false;

		if(\Common\Filter::hasTags($this->getUserName())) {
			$this->message .= "<p>Användarnamnet innehåller ogiltiga tecken.</p>";
		}

		else {

			if(strlen($this->getUserName()) < $userNameMinLength) {
				$this->message .= "<p>Användarnamnet har för få tecken. Minst " . $userNameMinLength . " tecken</p>";
				$badInput = true;
			}

			if(strlen($this->getUserName()) > $userNameMaxLength) {
				$this->message .= "<p>Användarnamnet har för många tecken. Max"  . $userNameMaxLength . " tecken</p>";
				$badInput = true;
			}

			if(strlen($this->getPassword()) < $passWordMinLength) {
				$this->message .= "<p>Lösenordet har för få tecken. Minst " . $passWordMinLength . " tecken</p>";
				$badInput = true;
			}

			if(strlen($this->getPassword()) > $passWordMaxLength) {
				$this->message .= "<p>Lösenordet har för många tecken. Max " . $passWordMaxLength . " tecken</p>";
				$badInput = true;
			}

			if(!$badInput) {
				$this->message .= "<p>Användarnamnet är redan upptaget</p>";
			}

		}	
	}

	/**
	 * From RegisterObserver
	 * @return void
	 */
	public function registrationOK() {
		$this->registrationOK = true;
		$_POST[self::$LOGIN_USERNAME] = $this->getUserName();
	}

	/**
	 * @return boolean
	 */
	public function userWantsToRegister() {
		return (isset($_GET[self::$REGISTER]) && !$this->registrationOK);
	}

	/**
	 * @return boolean
	 */
	public function userIsRegistrating() {
		if(isset($_POST[self::$SUBMIT]) && $this->getPassword() != $this->getVerifiedPassword()) {
			$this->message .= "<p>Lösenorden matchar inte.</p>";
			return false;
		}

		return isset($_POST[self::$SUBMIT]);
	}

	/**
	 * @return boolean
	 */
	public function userRegistrated() {
		return $this->registrationOK;
	}

	/**
	 * @return string HTML
	 */
	public function getRegisterButton() {
		return "<p><a href='?" . self::$REGISTER . "'>Registrera ny användare</a></p>";
	}
}
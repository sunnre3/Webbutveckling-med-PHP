<?php

namespace login\model;

require_once("TemporaryPassword.php");
require_once("TemporaryPasswordServer.php");
require_once("TemporaryPasswordClient.php");
require_once("UserName.php");
require_once("Password.php");

class UserCredentials {
	
	/**
	 * @var UserName
	 */
	private $userName; //unique username

	/**
	 * @var Password
	 */
	private $password; //original password but encrypted
	private $temporaryPassword; //can be stored at client
	

	/**
	 * @todo split into create methods!
	 * 
	 * @param UserName            $userName         
	 * @param Password            $password          
	 * @param TemporaryPassword $temporaryPassword
	 */
	private function __construct($userName, 
								 $password, 
								 $temporaryPassword) {
		$this->userName = $userName;
		$this->password = $password;
		
		
		if ($temporaryPassword != null) {
			$this->temporaryPassword = $temporaryPassword;
		}
	}

	public static function create($userName, $password) {
		return new UserCredentials($userName, $password, new TemporaryPasswordServer());
	}

	public static function createFromClientData($userName, 
												$password) {
		return new UserCredentials($userName, $password, TemporaryPasswordClient::emptyPassword());
	}

	public static function createWithTempPassword($userName, 
												  $temporaryPassword) {
		return new UserCredentials($userName, Password::emptyPassword(), $temporaryPassword);
	}

	

	/**
     * @todo  really unserialization of this object...
     * @param  String $string 
     * @return UserCredentials
     */
    public static function fromString($string) {
        $unencoded = urldecode($string);
        $parts = explode("<>", $unencoded);
        return new UserCredentials( new UserName($parts[0]), 
        							Password::fromEncryptedString($parts[1]), 
        							TemporaryPasswordServer::fromString($parts[2]));
    }
	
	/**
	 * @return UserName
	 */
	public function getUserName() {
		return $this->userName;
	}
	
	/**
	 * @return TemporaryPassword
	 */
	public function getTemporaryPassword() {
		return $this->temporaryPassword;
	}
	
	public function newTemporaryPassword() {
		$this->temporaryPassword = new TemporaryPasswordServer();
	}

	public function userNameExists(UserCredentials $other) {
		return strtolower($this->userName) == strtolower($other->userName);
	}
	
	/**
	 * @param  UserCredentials $other 
	 * @return boolean        
	 */
	public function isSame($other) {
		$userNameIsSame = $this->userName == $other->userName;
		$encryptedPWSame = $this->password == $other->password;
		$tempPasswordsMatch =  	$this->temporaryPassword->doMatch($other->temporaryPassword);	
		
		if ($userNameIsSame && ($encryptedPWSame || $tempPasswordsMatch)) {
			return true;
		}
		return false;
	}
	
	
	
	

	/**
	 * @todo  really serialization of this object...
	 * @return String
	 */
	public function toString() {
            return urlencode($this->userName . "<>" . 
                       $this->password . "<>" . 
                       $this->temporaryPassword->toString());
    }
    
    
}

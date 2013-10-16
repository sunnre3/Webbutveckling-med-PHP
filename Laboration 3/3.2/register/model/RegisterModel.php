<?php

namespace register\model;

require_once("./login/model/UserList.php");

class RegisterModel {
	/**
	 * @var \login\model\UserList
	 */
	private $allUsers;

	public function __construct() {
		$this->allUsers = new \login\model\UserList();
	}

	public function doRegisterUser(\login\model\UserCredentials $userCredentials,
									\register\model\RegisterObserver $observer) {
		try {
			//Make sure the username is available
			$vaildUser = $this->allUsers->isUserNameAvailable($userCredentials);

			//Register user
			$this->allUsers->saveUser($userCredentials);

			//Everything is OK
			$observer->registrationOK();
		}

		catch(\Exception $e) {
			\Debug::log("registration failed", false, $e->getMessage());
			throw $e;
		}
	}
}
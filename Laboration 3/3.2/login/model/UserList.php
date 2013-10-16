<?php

namespace login\model;


require_once("UserCredentials.php");
require_once("common/model/PHPFileStorage.php");

/**
 * represents All users in the system
 *
 */
class UserList {
	/**
	 * Temporary solution with only one user "Admin" in PHPFileStorage
 	 * You might want to use a database instead.
	 * @var \common\model\PHPFileStorage
	 */
	private $usersFile;

	/**
	 * We only have one user in the system right now.
	 * @var array of UserCredentials
	 */
	private $users;


	public function  __construct( ) {
		$this->users = array();
		
		$this->loadUsers();

		if(empty($this->users))
			$this->loadAdmin();
	}

	/**
	 * Do we have this user in this list?
	 * @throws  Exception if user provided is not in list
	 * @param  UserCredentials $fromClient
	 * @return UserCredentials from list
	 */
	public function findUser($fromClient) {
		foreach($this->users as $user) {
			if ($user->isSame($fromClient) ) {
				\Debug::log("found User");
				return  $user;
			}
		}
		throw new \Exception("could not login, no matching user");
	}

	/**
	 * Is the username available?
	 * @param  \UserCredentials  $fromClient
	 * @return UserCredentials
	 */
	public function isUserNameAvailable($fromClient) {
		foreach($this->users as $user) {
			if($user->userNameExists($fromClient)) {
				throw new \Exception("could not register, username already exists");
			}
		}

		return $fromClient;
	}

	public function update($changedUser) {
		//this user needs to be saved since temporary password changed
		$this->usersFile->writeItem($changedUser->getUserName(), $changedUser->toString());

		\Debug::log("wrote changed user to file", true, $changedUser);
		$this->users[$changedUser->getUserName()->__toString()] = $changedUser;
	}

	/**
	 * Save a valid user to the file
	 * @param  \login\UserCredentials $user
	 * @return void
	 */
	public function saveUser(\login\model\UserCredentials $user) {
		//Assert username is available
		assert($this->isUserNameAvailable($user));

		//Write to file
		$this->usersFile->writeItem($user->getUserName(), $user->toString());

		//Save to array
		$this->users[$user->getUserName()->__toString()] = $user;
	}

	/**
	 * Temporary function to store "Admin" user in file "data/admin.php"
	 * If no file is found a new one is created.
	 * 
	 * @return [type] [description]
	 */
	private function loadAdmin() {
		
		$this->usersFile = new \common\model\PHPFileStorage("data/users.php");
		try {
			//Read admin from file
			$adminUserString = $this->usersFile->readItem("Admin");
			$admin = UserCredentials::fromString($adminUserString);

		} catch (\Exception $e) {
			\Debug::log("Could not read file, creating new one", true, $e);

			//Create a new user
			$userName = new UserName("Admin");
			$password = Password::fromCleartext("Password");
			$admin = UserCredentials::create( $userName, $password);
			$this->update($admin);
		}

		$this->users[$admin->getUserName()->__toString()] = $admin;
	}

	/**
	 * Loads all users found in "data/users.php"
	 * @return void
	 */
	private function loadUsers() {
		$this->usersFile = new \common\model\PHPFileStorage("data/users.php");

		$usersArray = $this->usersFile->readAll();

		foreach ($usersArray as $key => $userString) {
			$user = UserCredentials::fromString($userString);
			$this->users[$user->getUserName()->__toString()] = $user;
		}
	}
}
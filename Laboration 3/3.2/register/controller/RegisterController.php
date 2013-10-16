<?php

namespace register\controller;

require_once("./register/model/RegisterModel.php");
require_once("./register/view/RegisterView.php");

class RegisterController {
	/**
	 * @var \register\model\RegisterModel
	 */
	private $model;

	/**
	 * @var \register\view\RegisterView
	 */
	private $view;

	public function __construct($registerView) {
		$this->model = new \register\model\RegisterModel();
		$this->view = $registerView;
	}

	/**
	 * @return void
	 */
	public function doRegisterUser() {
		if($this->view->userWantsToRegister() &&
			$this->view->userIsRegistrating()) {
			try {
				$credentials = $this->view->getUserCredentials();
				$this->model->doRegisterUser($credentials, $this->view);
			}

			catch(\Exception $e) {
				\Debug::log("Registration failed", false, $e->getMessage());
				$this->view->registrationFailed();
			}
		}
	}

	/**
	 * @return boolean
	 */
	public function userWantsToRegister() {
		return $this->view->userWantsToRegister();
	}
}
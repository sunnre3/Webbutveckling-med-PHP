<?php

namespace login\controller;

require_once("./login/model/LoginModel.php");
require_once("./login/view/LoginView.php");
require_once("./register/view/RegisterView.php");

class LoginController {
	
	/**
	 * @var \login\model\LoginModel
	 */
	private $model;

	/**
	 * @var \login\view\LoginView
	 */
	private $view;

	/**
	 * @var \register\view\RegisterView;
	 */
	private $registerView;
	
	/**
	 * @param loginviewLoginView $view
	 */
	public function __construct($view, $registerView) {
		$this->model = new \login\model\LoginModel();;
		$this->view = $view;
		$this->registerView = $registerView;
	}
	
	
	/**
	 * Facade
	 * @return boolean
	 */
	public function isLoggedIn() {
		return $this->model->isLoggedIn();
	}
	
	/** 
	 * Facade
	 * @return \login\model\UserCredentials
	 */
	public function getLoggedInUser() {
		return $this->model->getLoggedInUser();
	}
	
	/**
	 * Handle input
	 * Make sure to log statechanges
	 *
	 * note this has no output, output goes through views that are called seperately
	 */
	public function doToggleLogin() {
		if ($this->model->isLoggedIn()) {
			\Debug::log("We are logged in");
			if ($this->view->isLoggingOut() ) {
				$this->model->doLogout();
				$this->view->doLogout();
				\Debug::log("We logged out");
			}
		} else {
			\Debug::log("We are not logged in");
			if ($this->registerView->userRegistrated()) {
				$this->view->registrationOK();
			}

			else if ($this->view->isLoggingIn() ) {
				try {
					$credentials = $this->view->getUserCredentials();
					$this->model->doLogin($credentials, $this->view);
					\Debug::log("Login succeded");
				} catch (\Exception $e) {
					\Debug::log("Login failed", false, $e->getMessage());
					$this->view->LoginFailed();
				}
			}
		}
	}
}

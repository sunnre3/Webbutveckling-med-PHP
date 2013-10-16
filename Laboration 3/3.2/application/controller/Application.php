<?php

namespace application\controller;

require_once("application/view/View.php");
require_once("register/controller/RegisterController.php");
require_once("login/controller/LoginController.php");


/**
 * Main application controller
 */
class Application {
	/**
	 * \view\view
	 * @var [type]
	 */
	private $view;

	/**
	 * @var \login\controller\LoginController
	 */
	private $loginController;

	/**
	 * @var \register\controller\RegisterController
	 */
	private $registerController;
	
	public function __construct() {
		$loginView = new \login\view\LoginView();
		$registerView = new \register\view\RegisterView();

		$this->registerController = new \register\controller\RegisterController($registerView);
		$this->loginController = new \login\controller\LoginController($loginView, $registerView);

		$this->view = new \application\view\View($loginView, $registerView);
	}
	
	/**
	 * @return \common\view\Page
	 */
	public function doFrontPage() {
		$this->registerController->doRegisterUser();
		$this->loginController->doToggleLogin();
	
		if ($this->loginController->isLoggedIn()) {
			$loggedInUserCredentials = $this->loginController->getLoggedInUser();
			return $this->view->getLoggedInPage($loggedInUserCredentials);
		} else if ($this->registerController->userWantsToRegister()) {
			return $this->view->getRegisterPage();
		} else {
			return $this->view->getLoggedOutPage();
		}
	}
}

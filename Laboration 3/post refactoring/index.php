<?php

	session_start();
	
	require_once("model/Login.php");
	require_once("view/Login.php");
	require_once("controller/Login.php");
	require_once("view/HTMLPage.php");
	
	$loginModel			= new \model\login();
	$loginView			= new \view\Login();
	$loginController	= new \controller\Login();
	$HTMLPage			= new \view\HTMLPage();
	
	if($loginModel->isUserLoggedIn()) {
		$htmlContent = $loginView->getLoggedIn($loginController->message);
		$title = "Laboration 3: inloggad";
	}
	
	else {
		$htmlContent = $loginView->getNotLoggedIn($loginController->message);
		$title = "Laboration 3: du är inte inloggad";
	}
	
	echo $HTMLPage->getPage($title, $htmlContent);
<?php

	require_once("controller/Login.php");
	require_once("view/Login.php");
	require_once("view/HTMLPage.php");
	
	$login		= new \controller\Login();
	$loginView	= new \view\Login();
	$HTMLPage	= new \view\HTMLPage();
	
	if($login->isLoggedIn()) {
		$htmlContent = $loginView->getLoggedIn($login->message);
		$title = "Laboration 1: inloggad";
	}
	
	else {
		$htmlContent = $loginView->getNotLoggedIn($login->message);
		$title = "Laboration 1: du är inte inloggad";
	}
	
	echo $HTMLPage->getPage($title, $htmlContent);
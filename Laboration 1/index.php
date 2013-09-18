<?php

	require_once("controller/Login.php");
	require_once("view/HTMLPage.php");
	
	$login		= new \controller\Login();
	$HTMLPage	= new \view\HTMLPage();
	
	if($login->isLoggedIn()) {
		$htmlContent = $HTMLPage->getLoggedIn($login->feedback);
	}
	
	else {
		$htmlContent = $HTMLPage->getNotLoggedIn($login->feedback);
	}
	
	echo $htmlContent;
	
?>
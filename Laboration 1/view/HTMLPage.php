<?php

namespace view;

setlocale(LC_ALL, 'swedish');

class HTMLPage {
	private function basicHTML($title, $body) {
		$date = utf8_encode(strftime('%A, den %#d %B &aring;r %Y. Klockan &auml;r [%H:%M:%S].'));
		return "<!DOCTYPE html>
		<html>
		
			<head>
				<title>$title</title>
				<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
			</head>
			
			<body>
				<h1>Laborationskod rk222cu</h1>
				$body
				<div><br>$date</div>
			</body>
			
		</html>
		";
	}
	
	public function getLoggedIn($feedback) {
		return $this->basicHTML('Laboration 1: Inloggad',
		'<h2>' . $_SESSION["username"] . ' är inloggad</h2>
		' . (isset($feedback) ? '<p>' . $feedback . '</p>' : '') . '
		<a href="index.php?logout">Logga ut</a>
		');
	}
	
	public function getNotLoggedIn($feedback) {
		return $this->basicHTML('Laboration 1: Inte inloggad',
		'<h2>Ej Inloggad</h2>
		<form method="post" action="index.php" name="loginform">
			<fieldset>
				<legend>Login - Skriv in användarnamn och lösenord</legend>
				
				' . (isset($feedback) ? '<p>' . $feedback . '</p>' : '') . '
				
				<label for="username">Namn: </label>
				<input id="username" name="username" type="text" value="' . (isset($_POST["username"]) ? $_POST["username"] : '') . '">
				
				<label for="password">Lösenord: </label>
				<input id="password" name="password" type="password">
				
				<label for="remember_me">Håll mig inloggad: </label>
				<input id="remember_me" name="remember_me" type="checkbox">
				
				<input id="submit" name="submit" type="submit" value="Logga in">
			</fieldset>
		</form>
		');
	}
}
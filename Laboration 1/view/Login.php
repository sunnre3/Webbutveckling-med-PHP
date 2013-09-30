<?php

namespace view;

class Login {
	/**
	 * Returns HTML for a logged in user
	 * @param  string $message
	 * @return string
	 */
	public function getLoggedIn($message) {
		return '
			<h2>' . $_SESSION["username"] . ' är inloggad</h2>
			' . (isset($message) ? '<p>' . $message . '</p>' : '') . '
			<a href="index.php?logout">Logga ut</a>
		';
	}
	
	/**
	 * Returns HTML for a user who isn't logged in
	 * @param  string $message
	 * @return string
	 */
	public function getNotLoggedIn($message) {
		return '
			<h2>Ej Inloggad</h2>
			<form method="post" action="index.php" name="loginform">
				<fieldset>
					<legend>Login - Skriv in användarnamn och lösenord</legend>
					
					' . (isset($message) ? '<p>' . $message . '</p>' : '') . '
					
					<label for="username">Namn: </label>
					<input id="username" name="username" type="text" 
					value="' . (isset($_POST["username"]) ? $_POST["username"] : '') . '">
					
					<label for="password">Lösenord: </label>
					<input id="password" name="password" type="password">
					
					<label for="remember_me">Håll mig inloggad: </label>
					<input id="remember_me" name="remember_me" type="checkbox">
					
					<input id="submit" name="submit" type="submit" value="Logga in">
				</fieldset>
			</form>
		';
	}
}
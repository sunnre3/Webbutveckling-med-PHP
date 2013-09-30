<?php

namespace view;

setlocale(LC_ALL, 'swedish');

class HTMLPage {
	/**
	 * Returns the basic HTML markup along with the accompanied title and body
	 * @param  string $title Page title
	 * @param  string $body  body content
	 * @return string
	 */
	public function getPage($title, $body) {
		$date = utf8_encode(
			strftime('%A, den %#d %B &aring;r %Y. Klockan &auml;r [%H:%M:%S].')
		);
		
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
}
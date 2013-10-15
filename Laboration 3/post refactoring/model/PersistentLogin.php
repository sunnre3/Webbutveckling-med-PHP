<?php

namespace model;

class PersistentLogin {
	/**
	 * @var string
	 */
	private static $cookieEndTimeFile = "cookie_endtime.txt";

	/**
	 * Saves the expected end time for a persistent login to file
	 * @param  int $endtime UNIX timestamp
	 * @return void
	 */
	public function saveEndTime($endtime) {
		file_put_contents(self::$cookieEndTimeFile, $endtime);
	}

	/**
	 * Validates the expected end time with current time
	 * @param  int $endtime UNIX timestamp
	 * @return boolean
	 */
	public function validateEndTime() {
		return (time() < file_get_contents(self::$cookieEndTimeFile));
	}
}
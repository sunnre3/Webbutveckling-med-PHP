<?php

namespace register\model;

/**
 * callback interface for registrating new user
 */
interface RegisterObserver {
	public function registrationFailed();
	public function registrationOK();
}
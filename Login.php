<?php

class Login {

	public $logins;

	public function __construct() {
        $loginfile = file_get_contents("config/login.txt");
		if ( ! session_id() ) @ session_start();
		foreach(explode("\n", $loginfile) as $l) {
			$line = preg_split('/\s+/', $l);
			$this->logins[] = array($line[0], $line[1]);
		}

	}

	public function isLoggedIn() {
		return isset($_SESSION["login"]);
	}

	public function login($login, $password, $register) {
		if($register) {
			$this->register($login, $password);
			return true;
		}
		foreach($this->logins as $l) {
			if($l[0]==$login && md5($password)==$l[1]) {
				$_SESSION["login"] = $l[0];
				return true;
			}
		}
		return false;
	}

	public function logout() {
		unset($_SESSION["login"]);
	}

	public function register($login, $password) {
		file_put_contents("../project_manager/config/login.txt", "\n$login ".md5($password), FILE_APPEND);
	}
}
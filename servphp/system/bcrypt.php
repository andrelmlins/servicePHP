<?php

	class Bcrypt{
		private $_cost = 8;

		function __construct($cost=8){
			$this->_cost = $cost;
		}

		public static function hash($string, $cost = null) {
			$salt = $this->generateRandomSalt();
			$hashString = sprintf('$%s$%02d$%s$', "2a", (int)$this->_cost, $salt);
			return crypt($string, $hashString);
		}

		private function generateRandomSalt() {
			$seed = md5(uniqid(mt_rand(), true));
			$salt = base64_encode($seed);
			$salt = str_replace('+', '.', $salt);
			return substr($salt, 0, 22);
		}

		public static function check($string, $hash) {
			return (crypt($string, $hash) === $hash);
		}
	}

?>
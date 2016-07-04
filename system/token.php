<?php

	class Token{

		private $_length;
		private $_con;
		private $_time;

		function __construct($length=32,$time=60,$con=null){
			$this->_length = $length;

			$this->_con = $con->getCon();
			$this->_time = $time;
		}

		public function generateToken(){
			$token = md5(uniqid(mt_rand(), true));
			return substr($token, 0, $this->_length);
		}

		public function createToken(){
			$stmt = $this->_con->prepare("CREATE TABLE IF NOT EXISTS token(token varchar(255),date datetime,primary key(token));");
		    $stmt->execute();

		    $token = $this->generateToken();

		    $stmt = $this->_con->prepare("INSERT INTO token (token,date) VALUES (?,now())");
		    $stmt->bindParam(1,$token);
		    $stmt->execute();

		    return $token;
		}

		public function validToken($token){
			$stmt = $this->_con->prepare("SELECT * FROM token WHERE token=? AND Minute(now())-Minute(date)<=?");
		    $stmt->bindParam(1,$token);
		    $stmt->bindParam(2,$this->_time);
		    $stmt->execute();
		    if($stmt->rowCount()<=0) return false;
		    else return true;
		}
	}

?>
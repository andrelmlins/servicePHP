<?php

	class Connection{
		private $_con;

		function __construct($host=null,$dbname=null,$user=null,$password=null,$port=null){
			if($host!=null){
				$this->connection($host,$dbname,$port,$user,$password);
				$this->options();
			}
			else{
				$this->_con = null;
			}
		}

		public function connection($host,$dbname,$port,$user,$password){
			$this->_con = new PDO("mysql:host=".$host.";dbname=".$dbname.";port=".$port, $user, $password);
		}

		public function getCon(){
			return $this->_con;
		}

		private function options(){
			$this->_con->exec("SET NAMES iso-8859-1");
			$this->_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
	}

?>
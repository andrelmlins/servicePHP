<?php

	class ServPHP{
		private $_connection;
		private $_directory;
		private $_system;
		private $_token;
		private $_noToken;

		function __construct($directory){
			$this->config();
			$this->_token = null;
			$this->_connection = new Connection();
			$this->_directory = $directory;
			$this->_system = new System($directory,$this->_connection);
		}

		public function token($noToken=array(),$length,$time){
			$erro = new StdClass();
			if($this->_connection!=null) $this->_token = new Token($length,$time,$this->_connection);
			else {
				$erro->erro="Não existe conexão com o banco de dados";
				echo json_encode($erro);
				exit;
			}
		}

		public function getToken(){
			$erro = new StdClass();
			if($this->_connection!=null) return $this->_token->createToken();
			else{
				$erro->erro="Não existe conexão com o banco de dados";
				echo json_encode($erro);
				exit;
			} 
		}

		public function connection($host,$dbname,$port,$user,$password){
			$this->_connection = new Connection($host,$dbname,$user,$password,$port);
			$this->_system->setConnection($this->_connection);
		}

		public function run(){
			$erro = new StdClass();
			if($this->_connection!=null){
				if($this->_token==null){
					$this->_system->call();
				}
				else{
					if(isset($_GET['token'])){
						if($this->_token->validToken($_GET['token'])){
							$this->_system->call();
						}
						else{
							$erro->erro="Token Inválido";
							echo json_encode($erro);
							exit;
						}
					}
					else{
						$erro->erro="Token Inválido";
						echo json_encode($erro);
						exit;
					}
				}
			}
			else{
				$erro->erro="Não existe conexão com o banco de dados";
				echo json_encode($erro);
				exit;
			}
		}

		private function config(){
			date_default_timezone_set('Brazil/East');
			require_once('system/define.php');
			require_once('system/system.php');
			require_once('system/connection.php');
			require_once('system/token.php');
		}
	}

?>
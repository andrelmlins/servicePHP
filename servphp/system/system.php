<?php

class System{
	private $_url;
	private $_token;
	private $_route;
	private $_directory;
	private $_connection;
	
	function __construct($directory,$connection){
		$this->_directory = $directory;
		$this->_connection = $connection;
	}
	
	private function getRoute($metodo,$routeCurrent){
		$archive = file(dirname(__FILE__)."/../route");
		$erro = new stdClass();
		$routeCurrent = explode("/",$routeCurrent);
		foreach($archive as $line){
			$line = preg_replace("/( )+/", " ",preg_replace("/(\t)+/", " ", $line));
			$line = explode(" ", $line);
			$details = new stdClass();

			if($line[0]=="#".$metodo){
				$status = true;
				$route = explode("/",$line[1]);
				if(count($routeCurrent)!=count($route)) continue;
				else{
					$details->params = new stdClass();
					$details->params->data = new stdClass();
					for($i=0;$i<count($route);$i++) {
						if(substr($route[$i],0,1)==":"){
							$param = substr($route[$i], 1);
							$details->params->data->$param = $routeCurrent[$i];
						}
						else if($route[$i]!=$routeCurrent[$i]) {
							$status = false;
							break;
						}
					}
				}
				if($status){
					$details->method = str_replace("#","",$line[0]);
					$aux = explode(".",$line[2]);
					$details->controller = $aux[0];
					$details->method = $aux[1];
					return $details;
				}
			}
		}
		$erro->erro = "Rota não existe";
		echo json_encode($erro);
		exit;
	}

	private function validate(){
		$method = $_SERVER['REQUEST_METHOD'];
		if(isset($_SERVER['REDIRECT_URL'])){
			$route = str_replace("/".$this->_directory, "", htmlspecialchars($_SERVER['REDIRECT_URL']));
			$details = $this->getRoute($method, $route);

			$details->params->getdata = new stdClass();
			while($param_result = current($_GET)){
				$param = key($_GET);
				$details->params->getdata->$param =  htmlspecialchars($param_result);
				next($_GET);
			}
			$this->_route = $details;
			$postdata = json_decode(file_get_contents("php://input"));
			$details->params->postdata = new stdClass();
			if(isset($postdata)) $details->params->postdata = $postdata;		
		}
		else{
			$erro = new stdClass();
			$erro->erro = "Rota não existe";
			echo json_encode($erro);
			exit;
		}
		
	}

	public function call(){
		$this->validate();
		$erro = new stdClass();
		if(file_exists(CONTROLLERS.$this->_route->controller.'.php')){

			require_once(CONTROLLERS.$this->_route->controller.'.php');

			if(class_exists($this->_route->controller)){
				$controller = new $this->_route->controller($this->_connection);
				$method = explode("(",$this->_route->method)[0];
				if(method_exists($this->_route->controller,$method)){
					$controller->$method($this->_route->params->data,$this->_route->params->getdata,$this->_route->params->postdata);
				}
				else{
					$erro->erro="Método da rota não existe";
					echo json_encode($erro);
					exit;
				}
			}
			else{
				$erro->erro="Classe da rota não existe";
				echo json_encode($erro);
				exit;
			}
		}
		else{
			$erro->erro="Classe da rota não existe";
			echo json_encode($erro);
			exit;
		}
	}

	public function setConnection($con){
		$this->_connection = $con;
	}
}
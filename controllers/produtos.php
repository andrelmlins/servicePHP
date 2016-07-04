<?php

	class Produtos{

		private $_con;

		function __construct($connection){
			$this->_con = $connection->getCon();
		}

		function get($getparams){
		    $stmt = $this->_con->prepare("SELECT c.nome as categoria,c.id as catid,ca.nome as cat,p.id, p.nome,f.nome as fabricante, p.foto,p.foto1,p.foto2,p.foto3,p.descricao,p.beneficios,p.comousar,p.sugestoes,p.medida, f.id as idfabricante FROM produto p LEFT OUTER JOIN fabricante f ON f.id=p.id_fabricante LEFT OUTER JOIN subcategoria c ON p.categoria=c.id LEFT OUTER JOIN categoria ca ON c.categoria=ca.id WHERE p.id=? order by p.nome asc");
		    $stmt->bindParam(1,$getparams->id);
		    $stmt->execute();
		    $results = $stmt->fetchAll(PDO::FETCH_ASSOC); 
			$json = json_encode($results); 
		    echo $json;
		}

	}
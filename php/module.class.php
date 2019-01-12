<?php

include_once("autoload.include.php");

class Module{

	protected $idModule;
	protected $libModule;
	protected $idDatePrj;
	protected $descModule;
	protected $passModule;


	public function getLibModule(){
		return $this->libModule;
	}

	public function getId(){
		return $this->idModule;
	}

	public function getDesc(){
		return $this->descModule;
	}

	static public function getModuleById($id){
		$stmt = myPDO::getInstance()->prepare(<<<SQL

			SELECT *
			FROM module
			WHERE idModule = :idMod
SQL
);
		$stmt->execute(array( ':idMod' => $id));
		$stmt->setFetchMode(PDO::FETCH_CLASS, 'Module');
		$res = $stmt->fetch();

		return $res;
	}

	static public function getModules(){

		$stmt = myPDO::getInstance()->prepare(<<<SQL

			SELECT *
			FROM module
SQL
);
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_CLASS, 'Module');
		$res = $stmt->fetchAll();

		return $res;
	}
}

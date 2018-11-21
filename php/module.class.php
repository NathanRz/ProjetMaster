<?php

include_once("autoload.include.php");
require_once("utils.php");

class Module{

	protected $idModule;
	protected $libModule;
	protected $descModule;
	protected $imgMod;
	protected $passModule;
	protected $startDate;
	protected $endDate;


	public function getLibModule(){
		return $this->libModule;
	}

	public function getId(){
		return $this->idModule;
	}

	public function getDesc(){
		return $this->descModule;
	}

	public function getImgMod(){
		return $this->imgMod;
	}

	public function getStartDate(){
		return $this->startDate;
	}

	public function getEndDate(){
		return $this->endDate;
	}

	static public function getModuleById($id){
		$stmt = myPDO::getInstance()->prepare(<<<SQL

			SELECT *
			FROM module
			WHERE idModule = :idMod
SQL
);

		$stmt->execute(array( ':idMod' => secureInput($id)));
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

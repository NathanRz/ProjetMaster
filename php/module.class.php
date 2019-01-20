<?php

include_once("autoload.include.php");
<<<<<<< HEAD
=======
require_once("utils.php");
>>>>>>> a25d73c679b929889f0b13c1df16a1260d59f921

class Module{

	protected $idModule;
	protected $libModule;
	protected $descModule;
	protected $imgMod;
	protected $passModule;
	protected $startDate;
	protected $endDate;
	protected $duree;


	public function getLibModule(){
		return $this->libModule;
	}

	public function getId(){
		return $this->idModule;
	}

	public function getDesc(){
		return $this->descModule;
	}

<<<<<<< HEAD
=======
	public function getImgMod(){
		return $this->imgMod;
	}

	public function getStartDate(){
		return $this->startDate;
	}

	public function getEndDate(){
		return $this->endDate;
	}

	public function getDuree(){
		return $this->duree;
	}

>>>>>>> a25d73c679b929889f0b13c1df16a1260d59f921
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
<<<<<<< HEAD
=======

>>>>>>> a25d73c679b929889f0b13c1df16a1260d59f921

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

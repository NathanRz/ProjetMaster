<?php

include_once("autoload.include.php");
require_once("utils.php");

/**
* Classe Module
* Map les données de la table module
*/
class Module{

	/**
	* @var int $idModule l'id du module
	*/
	protected $idModule;
	/**
	* @var string $libModule le libellé du module
	*/
	protected $libModule;
	/**
	* @var string $descModule la description du module
	*/
	protected $descModule;
	/**
	* @var string $imgMod le chemin d'accès à l'image du module
	*/
	protected $imgMod;
	/**
	* @var string $passModule le mot de passe du module
	*/
	protected $passModule;
	/**
	* @var Date $startDate date d'ouverture des projets pour le module
	*/
	protected $startDate;
	/**
	* @var Date $endDate date de fermeture des projets pour le module
	*/
	protected $endDate;
	/**
	* @var Time $duree durée d'un projet
	*/
	protected $duree;

	/**
	* Getter sur le libellé
	* @return string $libModule le libellé du module
	*/
	public function getLibModule(){
		return $this->libModule;
	}

	/**
	* Getter sur l'id du module
	* @return int $idModule l'id du module
	*/
	public function getId(){
		return $this->idModule;
	}

	/**
	* Getter sur la description
	* @return int $descModule la description du module
	*/
	public function getDesc(){
		return $this->descModule;
	}

	/**
	* Getter sur le chemin d'accès a l'image du module
	* @return string $imgMod le chemin d'accès à l'image du module
	*/
	public function getImgMod(){
		return $this->imgMod;
	}

	/**
	* Getter sur la date d'ouverture des projets
	* @return Date $startDate la date d'ouverture des projets
	*/
	public function getStartDate(){
		return $this->startDate;
	}

	/**
	* Getter sur le mot de passe du module
	* @return string $passModule le mot de passe du module
	*/
	public function getPassModule(){
		return $this->passModule;
	}

	/**
	* Getter sur la date de fermeture des projets
	* @return Date $endDate la date de fermeture des projets
	*/
	public function getEndDate(){
		return $this->endDate;
	}

	/**
	* Getter sur la durée d'un projet
	* @return Time $duree la durée d'un projet
	*/
	public function getDuree(){
		return $this->duree;
	}

	/**
  * Récupérer le module par son identifiant
  * @param int $id l'id du module
  * @return Module res le module
  */
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

	/**
  * Récupérer tous les modules
  * @return array $res les modules
  */
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

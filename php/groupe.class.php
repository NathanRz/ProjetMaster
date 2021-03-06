<?php

include_once("autoload.include.php");
require_once("utils.php");

/**
* Classe Groupe
* Map les données de la groupe
*/
class Groupe{
  /**
  * 1 = TD / 2 = TP
  */

  /**
	* @var int $idGroupe l'id du groupe
	*/
  protected $idGroupe;
  /**
	* @var int $idModule l'id du module
	*/
  protected $idModule;
  /**
	* @var string $libGroupe le libellé du groupe
	*/
  protected $libGroupe;
  /**
	* @var int $typeGroupe le type de groupe(1:TD / 2:TP)
	*/
  protected $typeGroupe;
  /**
	* @var Time $horaireDeb l'horaire de début du TP/TD
	*/
  protected $horaireDeb;
  /**
	* @var Time $duree la durée d'un TP/TD
	*/
  protected $duree;

  /**
  * Getter sur l'identifiant du groupe
  * @return int $idGroupe l'id du groupe
  */
  public function getId(){
    return $this->idGroupe;
  }

  /**
  * Getter sur l'identifiant du module
  * @return int $idModule l'id du module
  */
  public function getIdModule(){
    return $this->idModule;
  }

  /**
  * Getter sur le libellé du groupe
  * @return string $libGroupe le libellé
  */
  public function getLib(){
    return $this->libGroupe;
  }

  /**
  * Getter sur le type de groupe
  * @return int $typeGroupe le type de groupe
  */
  public function getType(){
    return $this->typeGroupe;
  }

  /**
  * Getter sur la durée du TP/TD
  * @return Time $duree la durée du TP/TD
  */
  public function getDuree(){
    return $this->duree;
  }

  /**
  * Getter sur le type de groupe
  * @return string "TD/TP" le type de groupe au format string
  */
  public function getTypeString(){
    if($this->typeGroupe == 1)
      return "TD";

    return "TP";
  }

  /**
  * Getter sur l'horaire de début du TD/TP
  * @return int $horaireDeb l'horaire de début du TD/TP
  */
  public function getHoraire(){
    return $this->horaireDeb;
  }


  /**
  * Récupérer le groupe par son identifiant
  * @param int $id l'id du groupe
  * @return Groupe $res le groupe
  */
  static public function getGroupeById($id){
		$stmt = myPDO::getInstance()->prepare(<<<SQL

			SELECT *
			FROM groupe
			WHERE idGroupe = :id
SQL
);

		$stmt->execute(array( ':id' => secureInput($id)));
		$stmt->setFetchMode(PDO::FETCH_CLASS, 'Groupe');
		$res = $stmt->fetch();

		return $res;
	}

  /**
  * Récupérer tous les groupes
  * @return array $res les groupes
  */
  static public function getAllGroupes(){


		$stmt = myPDO::getInstance()->prepare(<<<SQL

			SELECT *
			FROM groupe
SQL
);
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_CLASS, 'Groupe');
		$res = $stmt->fetchAll();

		return $res;
	}

  /**
  * Récupérer tous les groupes d'un module
  * @param int $idModule l'id du module
  * @return array $res les groupes
  */
  static public function getAllGrpForModule($idModule){


		$stmt = myPDO::getInstance()->prepare(<<<SQL

			SELECT *
			FROM groupe
      WHERE idModule = :id
SQL
);
		$stmt->execute(array( ':id' => secureInput($idModule)));
		$stmt->setFetchMode(PDO::FETCH_CLASS, 'Groupe');
		$res = $stmt->fetchAll();

		return $res;
	}

  /**
  * Récupérer tous les groupes d'un certain type d'un module
  * @param int $idModule l'id du module
  * @param int $type le type de groupe
  * @return array $res les groupes
  */
  static public function getAllGrpForModuleByType($idModule,$type){


		$stmt = myPDO::getInstance()->prepare(<<<SQL

			SELECT *
			FROM groupe
      WHERE idModule = :id
      AND typeGroupe = :type
SQL
);
		$stmt->execute(array( ':id' => secureInput($idModule),
                          ':type'  => secureInput($type)));
		$stmt->setFetchMode(PDO::FETCH_CLASS, 'Groupe');
		$res = $stmt->fetchAll();

		return $res;
	}

  /**
  * Récupérer les groupes de TD d'un étudiant pour un module
  * @param int $idEtu l'id de l'étudiant
  * @param int $idMod l'id du module
  * @return array $res les groupes
  */
  static public function getGrpTDByIdEtu($idEtu, $idMod){
    $stmt = myPDO::getInstance()->prepare(<<<SQL
      SELECT * FROM groupe g, membre m, etudiant e
      WHERE e.idEtudiant = m.idEtudiant
      AND m.idGroupe = g.idGroupe
      AND e.idEtudiant = :id
      AND g.typeGroupe = 1
      AND g.idModule = :idM
SQL
);
    $stmt->execute(array(':id' => secureInput($idEtu),
                         ':idM' => secureInput($idMod)));
    $stmt->setFetchMode(PDO::FETCH_CLASS, "Groupe");

    return $stmt->fetch();
  }

  /**
  * Récupérer les groupes de TP d'un étudiant pour un module
  * @param int $idEtu l'id de l'étudiant
  * @param int $idMod l'id du module
  * @return array $res les groupes
  */
  static public function getGrpTPByIdEtu($idEtu, $idMod){
    $stmt = myPDO::getInstance()->prepare(<<<SQL
      SELECT * FROM groupe g, membre m, etudiant e
      WHERE e.idEtudiant = m.idEtudiant
      AND m.idGroupe = g.idGroupe
      AND e.idEtudiant = :id
      AND g.typeGroupe = 2
      AND g.idModule = :idM
SQL
);
    $stmt->execute(array(':id' => secureInput($idEtu),
                         ':idM' => secureInput($idMod)));
    $stmt->setFetchMode(PDO::FETCH_CLASS, "Groupe");

    return $stmt->fetch();
  }

  /**
  * Supprimer un groupe de TP/TD
  * @param int $idGrp l'id du groupe
  */
  static public function removeGroup($idGrp){

    $stmt = myPDO::getInstance()->prepare(<<<SQL

			DELETE FROM groupe
      WHERE idGroupe = :id
SQL
);
		$stmt->execute(array( ':id' => secureInput($idGrp)));



  }

  /**
  * Ajouter un groupe de TP/TD
  * @param int $idMod l'id du module
  * @param string $lib le libellé
  * @param int $type le type de groupe
  * @param Time $horaire l'horaire de début
  * @param string $duree la durée du TP/TD
  */
  static public function addGroup($idMod, $lib, $type, $horaire, $duree){
      $stmt = myPDO::getInstance()->prepare(<<<SQL
        INSERT INTO groupe VALUES(null, :idMod, :lib, :type, :horaire, :duree)
SQL
  );
      $stmt->execute(array(":idMod" => secureInput($idMod),
                           ":lib" => secureInput($lib),
                           ":type" => secureInput($type),
                           ":horaire" => secureInput($horaire),
                           ":duree" => secureInput($duree)));

  }
}

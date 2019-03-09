<?php

include_once("autoload.include.php");
require_once("utils.php");

class Groupe{

  protected $idGroupe;
  protected $idModule;
  protected $libGroupe;

  /**
  * 1 = TD / 2 = TP
  */
  protected $typeGroupe;
  protected $horaireDeb;
  protected $duree;

  public function getId(){
    return $this->idGroupe;
  }

  public function getIdModule(){
    return $this->idModule;
  }

  public function getLib(){
    return $this->libGroupe;
  }


  public function getType(){
    return $this->typeGroupe;
  }

  public function getDuree(){
    return $this->duree;
  }

  public function getTypeString(){
    if($this->typeGroupe == 1)
      return "TD";

    return "TP";
  }

  public function getHoraire(){
    return $this->horaireDeb;
  }


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

  //ajouter en fonction de idMod
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

  //ajouter en fonction de idMod
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

  static public function removeGroup($idGrp){
    $stmt = myPDO::getInstance()->prepare(<<<SQL

			DELETE FROM groupe
      WHERE idGroupe = :id
SQL
);
		$stmt->execute(array( ':id' => secureInput($idGrp)));

  }

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

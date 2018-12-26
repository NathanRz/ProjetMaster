<?php

include_once("autoload.include.php");
require_once("utils.php");

class Groupe{

  protected $idGroupe;
  protected $idModule;
  protected $libGroupe;
  protected $typeGroupe;
  protected $horaireDeb;

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


  static public function removeGroup($idGrp){
    $stmt = myPDO::getInstance()->prepare(<<<SQL

			DELETE FROM groupe
      WHERE idGroupe = :id
SQL
);
		$stmt->execute(array( ':id' => secureInput($idGrp)));

  }

  static public function addGroup($idMod, $lib, $type, $horaire){
      $stmt = myPDO::getInstance()->prepare(<<<SQL
        INSERT INTO groupe VALUES(null, :idMod, :lib, :type, :horaire)
SQL
  );
      $stmt->execute(array(":idMod" => secureInput($idMod),
                           ":lib" => secureInput($lib),
                           ":type" => secureInput($type),
                           ":horaire" => secureInput($horaire)));

  }
}

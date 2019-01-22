<?php

include_once("autoload.include.php");
require_once("utils.php");

class GroupeProjet{
  protected $idGroupePrj;
  protected $idModule;
  protected $idProjet;
  protected $etudiants = array();


  public function getEtudiants(){
    return $this->etudiants;
  }

  public function getIdGroupePrj(){
    return $this->idGroupePrj;
  }

  public function getIdModule(){
    return $this->idModule;
  }

  public function getIdProjet(){
    return $this->idProjet;
  }

  public static function getGroupePrjById($id){
    $grp = new GroupeProjet();
    $stmt = myPDO::getInstance()->prepare(<<<SQL
      SELECT *
      FROM groupeprojet
      WHERE idGroupePrj = :id
SQL
);

    $stmt->execute(array('id' => secureInput($id)));
    $res = $stmt->fetch();
    $grp->idGroupePrj = $res['idGroupePrj'];
    $grp->idModule = $res['idModule'];
    $grp->idProjet = $res['idProjet'];

    $stmt = myPDO::getInstance()->prepare(<<<SQL
      SELECT e.idEtudiant, nom, prenom
      FROM appartenir a, etudiant e
      WHERE a.idEtudiant = e.idEtudiant
      AND a.idGroupePrj = :id
SQL
);
    $stmt->execute(array('id' => secureInput($id)));

    $grp->etudiants= $stmt->fetchAll();

    return $grp;
  }

  public static function getGroupePrjByModAndEtu($idMod, $idEtu){
    $stmt = myPDO::getInstance()->prepare(<<<SQL
      SELECT g.idGroupePrj FROM appartenir a, groupeprojet g
      WHERE a.idGroupePrj = g.idGroupePrj
      AND g.idModule = :idM
      AND a.idEtudiant = :idE
SQL
);

    $stmt->execute(array(':idM' => secureInput($idMod),
                         ':idE' => secureInput($idEtu)));
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $res = $stmt->fetch();

    $grp = GroupeProjet::getGroupePrjById($res['idGroupePrj']);

    $stmt = myPDO::getInstance()->prepare(<<<SQL
      SELECT e.idEtudiant, nom, prenom
      FROM appartenir a, etudiant e
      WHERE a.idEtudiant = e.idEtudiant
      AND a.idGroupePrj = :id
SQL
);
    $stmt->execute(array('id' => secureInput($grp->getIdGroupePrj())));
    $stmt->setFetchMode(PDO::FETCH_CLASS, "Etudiant");
    $grp->etudiants= $stmt->fetchAll();

    if($grp->idGroupePrj == null)
      $grp = null;
    return $grp;
  }

  public static function getAllGroupeProjet(){

    $grps = array();
    $stmt = myPDO::getInstance()->prepare(<<<SQL
      SELECT *
      FROM groupeprojet
SQL
);
    $stmt->execute();
    $res = $stmt->fetchAll();

    foreach ($res as $g) {
      $grp = new GroupeProjet();
      $grp->idGroupePrj = $g['idGroupePrj'];
      $grp->idModule = $g['idModule'];
      $grp->idProjet = $g['idProjet'];

      $stmt = myPDO::getInstance()->prepare(<<<SQL
        SELECT e.idEtudiant, nom, prenom
        FROM appartenir a, etudiant e
        WHERE a.idEtudiant = e.idEtudiant
        AND a.idGroupePrj = :id
SQL
      );

      $stmt->execute(array('id' => $grp->idGroupePrj));

      $grp->etudiants = $stmt->fetchAll();

      array_push($grps, $grp);
    }

    return $grps;
  }

  public static function addGroupePrj($idMod, $etu){
    $stmt = myPDO::getInstance()->prepare(<<<SQL
      INSERT INTO groupeprojet VALUES(null,:idM,null)
SQL
);
    $stmt->execute(array(':idM' => secureInput($idMod)));
    $id = myPDO::getInstance()->lastInsertId();
    foreach ($etu as $e) {
      $stmt = myPDO::getInstance()->prepare(<<<SQL
        INSERT INTO appartenir VALUES(:idE,:idG)
SQL
);
      $stmt->execute(array(':idE' => $e->getId(),
                           ':idG' => $id));

    }
  }

  public static function getGrpByidMod($idMod){
    $stmt = myPDO::getInstance()->prepare(<<<SQL
      SELECT *
      FROM groupeprojet
      WHERE idModule = :id
SQL
);

    $stmt->execute(array('id' => secureInput($idMod)));
    $res = $stmt->fetchAll();

    for($i = 0; $i < count($res); $i++) {
      $stmt = myPDO::getInstance()->prepare(<<<SQL
        SELECT idEtudiant
        FROM appartenir
        WHERE idGroupePrj = :idGrp
SQL
  );
      $stmt->execute(array('idGrp' => $res[$i]["idGroupePrj"]));
      $res[$i]["etudiants"] = $stmt->fetchAll();
    }

    for($i = 0; $i < count($res); $i++) {
      for($j = 0; $j < count($res[$i]["etudiants"]); $j++) {
        $stmt = myPDO::getInstance()->prepare(<<<SQL
          SELECT *
          FROM etudiant
          WHERE idEtudiant = :idEtud
SQL
    );
        $stmt->execute(array('idEtud' => $res[$i]["etudiants"][$j]["idEtudiant"]));
        $res[$i]["etudiants"][$j] = $stmt->fetch();
      }
    }

    for($i = 0; $i < count($res); $i++) {
      for($j = 0; $j < count($res[$i]["etudiants"]); $j++) {
        $stmt = myPDO::getInstance()->prepare(<<<SQL
          SELECT g.typeGroupe, g.libGroupe
          FROM membre m, groupe g
          WHERE m.idGroupe = g.idGroupe
          AND m.idEtudiant = :idEtud
SQL
    );
        $stmt->execute(array('idEtud' => $res[$i]["etudiants"][$j]["idEtudiant"]));
        $res[$i]["etudiants"][$j]["groupe"] = $stmt->fetchAll();
      }
    }




    return $res;
  }
}

<?php

include_once("autoload.include.php");
require_once("utils.php");

public class GroupeProjet{
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

  public function getGroupePrjById($id){
    $grp = new GroupeProjet();
    $stmt = myPDO::getInstance()->prepare(<<<SQL
      SELECT *
      FROM groupeprojet
      WHERE idGroupePrj = :id
SQL
);

    $stmt->execute(array('id' => secureInput($id)));
    $res->fetch();
    $grp->idGroupe = $res[0]['idGroupe'];
    $grp->idModule = $res[0]['idModule'];
    $grp->idProjet = $res[0]['idProjet'];

    $stmt = myPDO::getInstance()->prepare(<<<SQL
      SELECT idEtudiant, nom, prenom
      FROM appartenir a, etudiant e
      WHERE a.idEtudiant = e.idEtudiant
      AND a.idGroupePrj = :id
SQL
);
    $stmt->execute(array('id' => secureInput($id)));

    $grp->etudiants= $stmt->fetchAll();

    return $grp;
  }

  public function getAllGroupeProjet(){

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
        SELECT idEtudiant, nom, prenom
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
}

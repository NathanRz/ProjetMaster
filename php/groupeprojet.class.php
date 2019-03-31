<?php

include_once("autoload.include.php");
require_once "mypdo.include.php";
require_once("utils.php");

/**
* Classe GroupeProjet
* Map les données de la table groupeprojet ainsi que les étudiants appartenant à ce groupe
*/
class GroupeProjet{

  /**
  * @var int idGroupePrj l'id du groupe de projet
  */
  protected $idGroupePrj;

  /**
  * @var int idModule l'id du module concernant le groupe de projet
  */
  protected $idModule;

  /**
  * @var int idProjet l'id du projet
  */
  protected $idProjet;

  /**
  * @var array etudiants les étudiants du groupe de projet
  */
  protected $etudiants = array();

  protected $projet;

  protected $idGroupe;

  protected $horaire;
  /**
  * Getter sur les etudiants du groupe de projet
  * @return array etudiants les étudiants
  */
  public function getEtudiants(){
    return $this->etudiants;
  }

  /**
  * Getter sur l'id du groupe projet
  * @return int idGroupePrj l'id du groupe de projet
  */
  public function getIdGroupePrj(){
    return $this->idGroupePrj;
  }

  /**
  * Getter sur l'id du module
  * @return int idModule l'id du module
  */
  public function getIdModule(){
    return $this->idModule;
  }

  /**
  * Getter sur l'id du projet
  * @return int idProjet l'id du projet
  */
  public function getIdProjet(){
    return $this->idProjet;
  }

  public function getProjet(){
    return $this->projet;
  }

  public function getIdGroupe(){
    return $this->idGroupe;
  }

  public function getHoraire(){
    return $this->horaire;
  }

  /**
  * Récupère un groupe de projet par son id
  * @param int id l'id du groupe de projet
  * @return GroupeProjet grp le groupe de projet
  */
  public static function getGroupePrjById($id, $idMod){
    $grp = new GroupeProjet();
    $stmt = myPDO::getInstance()->prepare(<<<SQL
      SELECT *
      FROM groupeprojet
      WHERE idGroupePrj = :id
SQL
);

    $stmt->execute(array('id' => secureInput($id)));
    $stmt->setFetchMode(PDO::FETCH_CLASS, "GroupeProjet");
    $grp = $stmt->fetch();

    /*$stmt = myPDO::getInstance()->prepare(<<<SQL
      SELECT e.idEtudiant, nom, prenom
      FROM appartenir a, etudiant e
      WHERE a.idEtudiant = e.idEtudiant
      AND a.idGroupePrj = :id
SQL
);
    $stmt->execute(array('id' => secureInput($id)));
    $stmt->setFetchMode(PDO::FETCH_CLASS, "Etudiant");*/
    $grp->etudiants= Etudiant::getEtudiantsByGrpPrj($grp->getIdGroupePrj(), $idMod);

    $grp->projet = Projet::getProjetByIdGrp($grp->getIdGroupePrj());

    return $grp;
  }

  /**
  * Récupère le groupe de projet d'un étudiant pour un module
  * @param int idMod l'id du module
  * @param int idEtu l'id de l'étudiant
  * @return array grp le groupe de projet
  */
  public static function getGroupePrjByModAndEtu($idMod, $idEtu){
    $stmt = myPDO::getInstance()->prepare(<<<SQL
      SELECT * FROM appartenir a, groupeprojet g
      WHERE a.idGroupePrj = g.idGroupePrj
      AND g.idModule = :idM
      AND a.idEtudiant = :idE
SQL
);

    $stmt->execute(array(':idM' => secureInput($idMod),
                         ':idE' => secureInput($idEtu)));
    $stmt->setFetchMode(PDO::FETCH_CLASS, 'GroupeProjet');
    $grp = $stmt->fetch();

    if($grp){
      $grp->etudiants= Etudiant::getEtudiantsByGrpPrj($grp->getIdGroupePrj());
      $grp->projet = Projet::getProjetByIdGrp($grp->getIdGroupePrj());
    }

    return $grp;
  }

  /**
  * Récupère tous les groupes de projet
  * @return array grps les groupes de projet
  */
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

      /*$stmt = myPDO::getInstance()->prepare(<<<SQL
        SELECT e.idEtudiant, nom, prenom
        FROM appartenir a, etudiant e
        WHERE a.idEtudiant = e.idEtudiant
        AND a.idGroupePrj = :id
SQL
      );

      $stmt->execute(array('id' => $grp->idGroupePrj));*/

      $grp->etudiants = Etudiant::getEtudiantsByGrpPrj($grp->getIdGroupePrj());

      array_push($grps, $grp);
    }

    return $grps;
  }

  /**
  * Ajoute à la BDD un nouveau groupe de projet
  * @param int idMod l'id du module
  * @param array etu les étudiants du groupe
  */
  public static function addGroupePrj($idMod, $etu){
    $stmt = myPDO::getInstance()->prepare(<<<SQL
      INSERT INTO groupeprojet VALUES(null,:idM,null,null,null)
SQL
);
    $stmt->execute(array(':idM' => secureInput($idMod)));
    $id = myPDO::getInstance()->lastInsertId();
    $grps = array();
    foreach ($etu as $e) {
      $stmt = myPDO::getInstance()->prepare(<<<SQL
        INSERT INTO appartenir VALUES(:idE,:idG)
SQL
);
      $stmt->execute(array(':idE' => $e->getId(),
                           ':idG' => $id));
      array_push($grps, $e->getGrpTP());
    }

    //On choisis aléatoirement le groupe de TP dans lequel le groupe de projet va passer la soutenance.
    $nb = array();
    foreach ($grps as $g) {
      $stmt = myPDO::getInstance()->prepare(<<<SQL
        SELECT count(idGroupePrj)
        FROM groupeprojet
        WHERE idGroupe = :id
SQL
      );
      $stmt->execute(array(':id' => secureInput($g->getId())));
      array_push($nb,array('nbSout' => $stmt->fetch(),
                           'idGroup' => $g->getId()));

    }

    $nbSoutenances = array_column($nb, 'nbSout');
    //on recupere le groupe ayant le moins de soutenances parmis les groupes des étudiants
    $min_array = $nb[array_search(min($nbSoutenances), $nbSoutenances)];

    $stmt = myPDO::getInstance()->prepare(<<<SQL
      UPDATE groupeprojet SET idGroupe = :id
      WHERE idGroupePrj = :idGP
SQL
);

    $stmt->execute(array(':id' => $min_array['idGroup'],
                         ':idGP' => $id));
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
        SELECT archive
        FROM projet
        WHERE idGroupePrj = :idGrp
SQL
  );
      $stmt->execute(array('idGrp' => $res[$i]["idGroupePrj"]));
      $res[$i]["archive"] = $stmt->fetchColumn();
    }

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

  /**
  * Retourne tous les groupes de projets d'un module avec leurs étudiants
  * @param int idMod
  * @return array grps
  */

  public static function getGroupePrjByMod($idMod){
    $stmt = myPDO::getInstance()->prepare(<<<SQL
      SELECT * FROM  groupeprojet
      WHERE idModule = :id
      ORDER BY horaire, idGroupePrj
SQL
);
    $stmt->execute(array(':id' => secureInput($idMod)));
    $stmt->setFetchMode(PDO::FETCH_CLASS, "GroupeProjet");
    $grps = $stmt->fetchAll();

    foreach ($grps as $g) {
      $g->etudiants = Etudiant::getEtudiantsByGrpPrj($g->getIdGroupePrj(),$idMod);
      $g->projet = Projet::getProjetByIdGrp($g->getIdGroupePrj());
    }

    return $grps;
  }

  /**
  * Retourne tous les groupes de projets qui n'ont pas encore rendu de projet d'un module avec leurs étudiants
  * @param int idMod
  * @return array grps
  */

  public static function getGroupePrjByModProj($idMod){
    $stmt = myPDO::getInstance()->prepare(<<<SQL
      SELECT * FROM  groupeprojet
      WHERE idModule = :id
      AND idProjet IS NULL
SQL
);
    $stmt->execute(array(':id' => secureInput($idMod)));
    $stmt->setFetchMode(PDO::FETCH_CLASS, "GroupeProjet");
    $grps = $stmt->fetchAll();

    foreach ($grps as $g) {
      $g->etudiants = Etudiant::getEtudiantsByGrpPrj($g->getIdGroupePrj(),$idMod);
      $g->projet = Projet::getProjetByIdGrp($g->getIdGroupePrj());
    }

    return $grps;
  }
}

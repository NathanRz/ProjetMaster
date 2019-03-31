<?php
/**
* Classe Etudiant
* Map les données de la table etudiant ainsi que les groupes auquels les étudiants appartiennent
*/
class Etudiant{

  /**
  * @var int idEtudiant l'id de l'étudiant
  */
  protected $idEtudiant;
  /**
  * @var string nom le nom de l'étudiant
  */
  protected $nom;
  /**
  * @var string prenom le prénom de l'étudiant
  */
  protected $prenom;
  /**
  * @var array grpTD les groupes de TD
  */
  protected $grpTD;
  /**
  * @var array grpTP les groupes de TP
  */
  protected $grpTP;

  /**
  * Getter sur l'id étudiant
  * @return int idEtudiant l'id de l'étudiant
  */
  public function getId(){
    return $this->idEtudiant;
  }

  /**
  * Getter sur le nom de l'étudiant
  * @return string nom le nom de l'étudiant
  */
  public function getNom(){
    return $this->nom;
  }

  /**
  * Getter sur le prénom de l'étudiant
  * @return string prenom le prénom de l'étudiant
  */
  public function getPrenom(){
    return $this->prenom;
  }

  /**
  * Getter sur les groupes de TD
  * @return array grpTD les groupes de TD de l'étudiant
  */
  public function getGrpTD(){
    return $this->grpTD;
  }

  /**
  * Getter sur les groupes de TP
  * @return array grpTP les groupes de TP de l'étudiant
  */
  public function getGrpTP(){
    return $this->grpTP;
  }

  /**
  * Ajoute un étudiant à la BD en assignant ses groupes de TP et TD pour le module
  * @param string nom le nom de l'étudiant
  * @param string prenom le prénom de l'étudiant
  * @param int grpTD l'id du groupe de TD
  * @param int grpTP l'id du groupe de TP
  * @param int idmod l'id du module
  */
  static public function addEtudiant($nom,$prenom,$grpTD,$grpTP,$idMod){
    $pdo =myPDO::getInstance();
    $stmt = $pdo->prepare(<<<SQL
      INSERT INTO etudiant VALUES(null, :n, :p);
SQL
);
    $stmt->execute(array(':n' => secureInput($nom),
                         ':p' => secureInput($prenom)));

    $id = $pdo->lastInsertId();

    $stmt = $pdo->prepare(<<<SQL
      SELECT * FROM etudiant
      WHERE idEtudiant = :id
SQL
);
    $stmt->execute(array(':id' => secureInput($id)));
    $stmt->setFetchMode(PDO::FETCH_CLASS,'Etudiant');
    $res = $stmt->fetch();

    $stmt = $pdo->prepare(<<<SQL
      INSERT INTO membre VALUES(:idG,:idE)
SQL
);
    $stmt->execute(array(':idG' => secureInput($grpTD),
                         ':idE' => secureInput($res->getId())));

    $stmt = $pdo->prepare(<<<SQL
      INSERT INTO membre VALUES(:idG,:idE)
SQL
);
    $stmt->execute(array(':idG' => secureInput($grpTP),
                         ':idE' => secureInput($res->getId())));

    if($res){
      $res->grpTD = Groupe::getGrpTDByIdEtu($res->getId(),$idMod);
      $res->grpTP = Groupe::getGrpTPByIdEtu($res->getId(),$idMod);
    }
    return $res;
  }

  /**
  * Récupérer tous les étudiants d'un module (ou bien de tous les modules)
  * @param int idmod l'id du module
  * @return array res le tableau d'étudiants
  */
  static public function getEtudiants($idMod = null){
    $stmt = myPDO::getInstance()->prepare(<<<SQL
      SELECT * FROM etudiant
SQL
);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_CLASS, 'Etudiant');
		$res = $stmt->fetchAll();
    if($idMod !== null){
      foreach ($res as $e) {
        $e->grpTD = Groupe::getGrpTDByIdEtu($e->getId(),$idMod);
        $e->grpTP = Groupe::getGrpTPByIdEtu($e->getId(),$idMod);
      }
    }

    return $res;
  }

  /**
  * Récupérer un étudiant d'un module
  * @param int id l'id de l'étudiant
  * @param int idMod l'id du module
  * @return Etudiant res l'étudiant
  */
  static public function getEtudiantById($id, $idMod){
    $stmt = myPDO::getInstance()->prepare(<<<SQL
      SELECT * FROM etudiant
      WHERE idEtudiant = :id
SQL
);
    $stmt->execute(array(':id' => secureInput($id)));
    $stmt->setFetchMode(PDO::FETCH_CLASS, 'Etudiant');
		$res = $stmt->fetch();

    $res->grpTD = Groupe::getGrpTDByIdEtu($res->getId(), $idMod);
    $res->grpTP = Groupe::getGrpTPByIdEtu($res->getId(), $idMod);

    return $res;
  }

  /**
  * Ajouter un groupe de TP ou de TD à un étudiant
  * @param int idE l'id de l'étudiant
  * @param int idGrp l'id du groupe
  */
  static public function addGroupToEtud($idE, $idGrp){
    $stmt = myPDO::getInstance()->prepare(<<<SQL
      INSERT INTO membre VALUES(:idG, :idE)
SQL
);
    $stmt->execute(array(':idE' => secureInput($idE),
                         ':idG' => secureInput($idGrp)));

  }

  /**
  * Récupérer un étudiant d'un module par son nom et prénom
  * @param string prenom le prénom
  * @param string nom le nom
  * @param int idMod l'id du module
  * @return Etudiant res l'étudiant
  */
  static public function getEtudiantByName($prenom, $nom, $idMod){
    $stmt = myPDO::getInstance()->prepare(<<<SQL
      SELECT * FROM etudiant
      WHERE nom = :n
      AND prenom = :p
SQL
);
    $stmt->execute(array(':n' => secureInput($nom),
                         ':p' => secureInput($prenom)));
    $stmt->setFetchMode(PDO::FETCH_CLASS, 'Etudiant');
		$res = $stmt->fetch();
    if($res != null){
      $res->grpTD = Groupe::getGrpTDByIdEtu($res->getId(), $idMod);
      $res->grpTP = Groupe::getGrpTPByIdEtu($res->getId(), $idMod);
    }

    return $res;
  }

  /**
  * Récupérer les étudiants d'un module par leur groupe de projet
  * @param int idGrpPrj l'id du groupe de projet
  * @param int idMod l'id du module
  * @return array etus les étudiants
  */
  static public function getEtudiantsByGrpPrj($idGrpPrj, $idMod){
    $stmt = myPDO::getInstance()->prepare(<<<SQL
      SELECT e.idEtudiant, e.nom, e.prenom  FROM etudiant e, appartenir a, groupeprojet g
      WHERE e.idEtudiant = a.idEtudiant
      AND a.idGroupePrj = g.idGroupePrj
      AND g.idGroupePrj = :id
SQL
);
    $stmt->execute(array(':id' => secureInput($idGrpPrj)));
    $stmt->setFetchMode(PDO::FETCH_CLASS, "Etudiant");
    $etus = $stmt->fetchAll();

    foreach ($etus as $e) {
      $e->grpTD = Groupe::getGrpTDByIdEtu($e->getId(), $idMod);
      $e->grpTP = Groupe::getGrpTPByIdEtu($e->getId(), $idMod);
    }
    return $etus;
  }

  /**
  * Supprimer un étudiant
  * On supprime également les groupes de projet auquels il appartient ainsi que les projets
  * @param int idEtu l'id de l'étudiant
  */
  static public function removeEtudiant($idEtu){
    $grpPrjs = GroupeProjet::getGroupePrjByEtu($idEtu);
    foreach ($grpPrjs as $g) {
      GroupeProjet::removeGrpPrj($g->getIdGroupePrj());
    }
    $stmt = myPDO::getInstance()->prepare(<<<SQL

      DELETE FROM etudiant WHERE idEtudiant = :id

SQL
    );
    $stmt->execute(array(':id' => secureInput($idEtu)));
  }
}

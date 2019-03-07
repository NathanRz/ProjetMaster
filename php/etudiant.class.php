<?php

class Etudiant{

  protected $idEtudiant;
  protected $nom;
  protected $prenom;
  protected $grpTD;
  protected $grpTP;

  public function getId(){
    return $this->idEtudiant;
  }

  public function getNom(){
    return $this->nom;
  }

  public function getPrenom(){
    return $this->prenom;
  }

  public function getGrpTD(){
    return $this->grpTD;
  }

  public function getGrpTP(){
    return $this->grpTP;
  }

  static public function addEtudiant($nom,$prenom,$grpTD,$grpTP){
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
    return $res;
  }

  static public function getEtudiants(){
    $stmt = myPDO::getInstance()->prepare(<<<SQL
      SELECT * FROM etudiant
SQL
);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_CLASS, 'Etudiant');
		$res = $stmt->fetchAll();
    foreach ($res as $e) {
      $e->grpTD = Groupe::getGrpTDByIdEtu($e->getId());
      $e->grpTP = Groupe::getGrpTPByIdEtu($e->getId());
    }

    return $res;
  }

  static public function getEtudiantById($id){
    $stmt = myPDO::getInstance()->prepare(<<<SQL
      SELECT * FROM etudiant
      WHERE idEtudiant = :id
SQL
);
    $stmt->execute(array(':id' => secureInput($id)));
    $stmt->setFetchMode(PDO::FETCH_CLASS, 'Etudiant');
		$res = $stmt->fetch();

    $res->grpTD = Groupe::getGrpTDByIdEtu($res->getId());
    $res->grpTP = Groupe::getGrpTPByIdEtu($res->getId());

    return $res;
  }

  static public function getEtudiantByName($prenom, $nom){
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

    $res->grpTD = Groupe::getGrpTDByIdEtu($res->getId());
    $res->grpTP = Groupe::getGrpTPByIdEtu($res->getId());

    return $res;
  }

  static public function getEtudiantsByGrpPrj($idGrpPrj){
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
      $e->grpTD = Groupe::getGrpTDByIdEtu($e->getId());
      $e->grpTP = Groupe::getGrpTPByIdEtu($e->getId());
    }
    return ;
  }
}

<?php

class Etudiant{

  protected $idEtudiant;
  protected $nom;
  protected $prenom;

  public function getId(){
    return $this->idEtudiant;
  }

  public function getNom(){
    return $this->nom;
  }

  public function getPrenom(){
    return $this->prenom;
  }

  static public function addEtudiant($nom,$prenom){
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
		$res = $stmt->fetchAll();

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

    return $res;
  }
}

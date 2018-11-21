<?php
include_once("autoload.include.php");

class Fichier{

  protected $idFichier;
  protected $nomFichier;
  protected $descFichier;
  protected $typeFichier;
  protected $cheminFichier;


  public function getId(){
    return $this->idFichier;
  }

  public function getNomFichier(){
    return $this->nomFichier;
  }

  public function getDescFichier(){
    return $this->descFichier;
  }

  public function getTypeFichier(){
    return $this->typeFichier;
  }

  public function getCheminFichier(){
    return $this->cheminFichier;
  }

  static public function getFichiersByModule(int $id){
    $stmt = myPDO::getInstance()->prepare(<<<SQL
      SELECT *
      FROM fichier
      WHERE idModule = :idMod
SQL
);
    $stmt->execute(array(':idMod' => $id));
    $stmt->setFetchMode(PDO::FETCH_CLASS, 'Fichier');
    $res = $stmt->fetchAll();

    return $res;
  }

  static public function getFichierById($id){
    $stmt = myPDO::getInstance()->prepare(<<<SQL
      SELECT *
      FROM fichier
      WHERE idFichier = :id
SQL
);
    $stmt->execute(array(':id' => $id));
    $stmt->setFetchMode(PDO::FETCH_CLASS, 'Fichier');
    $res = $stmt->fetch();

    return $res;
  }

}

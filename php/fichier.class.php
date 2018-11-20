<?php
include_once("autoload.include.php");

class Fichier{

  protected $idFichier;
  protected $nomFichier;
  protected $descFichier;
  protected $typeFichier;


  public function getNomFichier(){
    return $this->nomFichier;
  }

  public function getDescFichier(){
    return $this->descFichier;
  }

  public function getTypeFichier(){
    return $this->typeFichier;
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


}

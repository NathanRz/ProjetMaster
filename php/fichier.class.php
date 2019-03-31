<?php
include_once("autoload.include.php");

/**
* Classe Fichier
* Map les données de la table fichier
*/
class Fichier{

  /**
	* @var int $idFichier l'id du fichier
	*/
  protected $idFichier;

  /**
	* @var string $nomFichier le nom du fichier
	*/
  protected $nomFichier;

  /**
  * @var string $descFichier la description du fichier
  */
  protected $descFichier;

  /**
  * @var string $typefichier le type du fichier (CM/TD/TP)
  */
  protected $typeFichier;

  /**
  * @var string $cheminFichier le chemin vers le fichier
  */
  protected $cheminFichier;

  /**
  * @var string $cheminImg le chemin vers l'image illustrant le fichier
  */
  protected $cheminImg;

  /**
  * @var string $cheminSource le chemin vers les sources accompagnant le fichier (seulement TD/TP)
  */
  protected $cheminSource;


  /**
  * Getter sur l'identifiant du fichier
  * @return int $idFichier l'id du fichier
  */
  public function getId(){
    return $this->idFichier;
  }

  /**
  * Getter sur le nom du fichier
  * @return string $nomFichier le nom du fichier
  */
  public function getNomFichier(){
    return $this->nomFichier;
  }

  /**
  * Getter sur la description du fichier
  * @return string $descFichier la description du fichier
  */
  public function getDescFichier(){
    return $this->descFichier;
  }

  /**
  * Getter sur le type du fichier
  * @return string $typefichier le type du fichier
  */
  public function getTypeFichier(){
    return $this->typeFichier;
  }

  /**
  * Getter sur le chemin du fichier
  * @return string $cheminFichier le chemin du fichier
  */
  public function getCheminFichier(){
    return $this->cheminFichier;
  }

  /**
  * Getter sur le chemin de l'image du fichier
  * @return string $cheminImg le chemin de l'image du  fichier
  */
  public function getCheminImg(){
    return $this->cheminImg;
  }

  /**
  * Getter sur le chemin des sources du fichier
  * @return string $cheminSource le chemin des sources du fichier
  */
  public function getCheminSource(){
    return $this->cheminSource;
  }

  /**
  * Récupère tous les fichiers correspondant à un module
  * @param int l'id du module
  * @return array res tous les fichiers
  */
  static public function getFichiersByModule($id){
    $stmt = myPDO::getInstance()->prepare(<<<SQL
      SELECT *
      FROM fichier
      WHERE idModule = :idMod
      ORDER BY idFichier;
SQL
);
    $stmt->execute(array(':idMod' => $id));
    $stmt->setFetchMode(PDO::FETCH_CLASS, 'Fichier');
    $res = $stmt->fetchAll();

    return $res;
  }

  /**
  * Récupèreun fichier par rapport à son id
  * @param int l'id du fichier
  * @return Fichier res le fichier
  */
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

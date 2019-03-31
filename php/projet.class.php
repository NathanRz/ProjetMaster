<?php

include_once("autoload.include.php");
require_once("utils.php");

/**
* Classe Projet
* Map les données de la table projet
*/
class Projet{
    /**
    * @var int $idProjet l'id du projet
    */
    protected $idProjet;
    /**
    * @var int $idGroupePrj l'id du groupe de projet
    */
    protected $idGroupePrj;
    /**
    * @var string $archive chemin d'accès aux sources du projet
    */
    protected $archive;
    /**
    * @var string $rapport chemin d'accès au rapport du projet
    */
    protected $rapport;
    /**
    * @var string $image chemin d'accès aux images du projet
    */
    protected $images;
    /**
    * @var Date $date date de dépot du projet
    */
    protected $date;

    /**
    * Getter sur l'identifiant du projet
    * @return int $idProjet l'id du projet
    */
    public function getIdProjet(){
      return $this->idProjet;
    }
    /**
    * Getter sur l'identifiant du groupe de projet
    * @return int $idGroupePrj l'id du groupe de projet
    */
    public function getIdGroupePrj(){
      return $this->idGroupePrj;
    }
    /**
    * Getter sur le chemin d'accès aux sources du projet
    * @return string $archive le chemin d'accès aux sources
    */
    public function getArchive(){
      return $this->archive;
    }
    /**
    * Getter sur le chemin d'accès au rapport du projet
    * @return string $rapport le chemin d'accès au rapport
    */
    public function getRapport(){
      return $this->rapport;
    }
    /**
    * Getter sur le chemin d'accès aux images du projet
    * @return string $images le chemin d'accès aux images
    */
    public function getImages(){
      return $this->images;
    }
    /**
  	* Getter sur la date de dépot du projet
  	* @return Date $date la date de dépot
  	*/
    public function getDate(){
      return $this->date;
    }

    /**
    * Récupérer le projet par l'identifiant du groupe de projet
    * @param int $id l'id du groupe de projet
    * @return Projet $res le projet
    */
    public static function getProjetByIdGrp($id){
      $stmt = myPDO::getInstance()->prepare(<<<SQL
          SELECT * FROM projet
          WHERE idGroupePrj = :id
SQL
);
      $stmt->execute(array(':id' => secureInput($id)));
      $stmt->setFetchMode(PDO::FETCH_CLASS, "Projet");

      return $stmt->fetch();
    }

    /**
    * Ajouter un projet
    * @param string $archiveLink le chemin d'accès aux sources
    * @param string $rapport le chemin d'accès au rapport
    * @param string $images le chemin d'accès aux images
    * @param int $idGrp l'id du groupe de projet
    */
    public static function addProjet($archiveLink, $rapport, $images ,$idGrp){
      $date = date('Y-m-d');
      $stmt = myPDO::getInstance()->prepare(<<<SQL
        INSERT INTO projet VALUES(null,:grp,:zip,:rap,:img,:d)
SQL
);
      $stmt->execute(array(':grp' => secureInput($idGrp),
                          ':zip' => secureInput($archiveLink),
                          ':rap' => secureInput($rapport),
                          ':img' => secureInput($images),
                          ':d' => $date));


      $stmt = myPDO::getInstance()->prepare(<<<SQL
        SELECT idProjet
        FROM projet
        WHERE idGroupePrj = :id
SQL
);
      $stmt->execute(array(':id' => secureInput($idGrp)));
      $res = $stmt->fetch();

      $stmt = myPDO::getInstance()->prepare(<<<SQL
        UPDATE groupeprojet
        SET idProjet = :idPrj
        WHERE idGroupePrj = :id
SQL
);
      $stmt->execute(array(':id' => secureInput($idGrp),
                           ':idPrj' => $res['idProjet']));
    }

    /**
    * Supprimer un projet
    * @param Projet $prj le projet à supprimer
    */
    public static function removeProjet($prj){

      if(file_exists("../" . $prj->getRapport()) && unlink("../" . $prj->getRapport())){};


      if(file_exists("../" . $prj->getArchive()) && unlink("../" . $prj->getArchive())){};


      if(file_exists("../" . $prj->getImages()) && unlink("../" . $prj->getImages())){};

    }
}

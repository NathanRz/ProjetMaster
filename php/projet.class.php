<?php

include_once("autoload.include.php");
require_once("utils.php");

class Projet{
    protected $idProjet;
    protected $idGroupePrj;
    protected $archive;
    protected $rapport;
    protected $images;
    protected $date;


    public function getIdProjet(){
      return $this->idProjet;
    }

    public function getIdGroupePrj(){
      return $this->idGroupePrj;
    }

    public function getArchive(){
      return $this->archive;
    }

    public function getRapport(){
      return $this->rapport;
    }

    public function getImages(){
      return $this->images;
    }
    public function getDate(){
      return $this->date;
    }

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
}

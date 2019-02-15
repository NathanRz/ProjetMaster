<?php

include_once("autoload.include.php");
require_once("utils.php");

class Projet{
    protected $idProjet;
    protected $idGroupePrj;
    protected $archive;
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

    public function getDate(){
      return $this->date;
    }

    public static function addProjet($archiveLink,$idGrp){
      $date = date('Y-m-d');
      $stmt = myPDO::getInstance()->prepare(<<<SQL
        INSERT INTO projet VALUES(null,:grp,:zip,:d)
SQL
);
      $stmt->execute(array(':grp' => secureInput($idGrp),
                          ':zip' => secureInput($archiveLink),
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

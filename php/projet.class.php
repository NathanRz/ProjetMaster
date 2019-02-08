<?php

include_once("autoload.include.php");
require_once("utils.php");

class Projet{
    protected $idProjet;
    protected $sources;
    protected $rapport;
    protected $images;

    public function getIdProjet(){
      return $this->idProjet;
    }

    public function getSources(){
      return $this->sources;
    }

    public function getRapport(){
      return $this->rapport;
    }

    public function getImages(){
      return $this->images;
    }

    public static function addProjet($id, $source, $rapport, $images){
      $stmt = myPDO::getInstance()->prepare(<<<SQL
        INSERT INTO projet VALUES(:id, :src, :rap, :imgs)
SQL
);
      $stmt->execute(array(':id' => secureInput($id),
                           ':src' => secureInput($source),
                           ':rap' => secureInput($rapport),
                           ':imgs' => secureInput($images)));
    }
}

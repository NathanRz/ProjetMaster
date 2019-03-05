<?php

include_once("autoload.include.php");
require_once "mypdo.include.php";
require_once "utils.php";

if(Admin::isConnected()){
  	if($_SERVER["REQUEST_METHOD"] == "POST"){
      $fichier = Fichier::getFichierById($_POST["idFichier"]);
      $path = "../" . $fichier->getCheminFichier();
      if(unlink($path)){
        $stmt = myPDO::getInstance()->prepare(<<<SQL
          DELETE FROM fichier
          WHERE idFichier = :id
SQL
);
        $stmt->execute(array(':id' => $fichier->getId()));
      }

      if($fichier->getCheminImg() != null)
        unlink("../" . $fichier->getCheminImg());
    }
}

header("Location: ../module.php?id={$_POST["idModule"]}");

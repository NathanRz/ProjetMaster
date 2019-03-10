<?php
include_once("autoload.include.php");
require_once "mypdo.include.php";
require_once "utils.php";

if(Admin::isConnected()){
  $stmt = myPDO::getInstance()->prepare(<<<SQL
    UPDATE groupeprojet
    SET idGroupe = :idGrp
    WHERE idGroupePrj = :idGrpPrj
SQL
);
  $stmt->execute(array(':idGrp' => secureInput($_GET['idGrpPass']),
                         ':idGrpPrj' => secureInput($_GET['idGrpPrj'])));

  echo Groupe::getGroupeById($_GET['idGrpPass'])->getLib();
}

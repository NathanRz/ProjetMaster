<?php

include_once("layout.class.php");
include_once("autoload.include.php");
include_once("utils.php");
session_start();
if(Admin::isConnected()){
  if($_SERVER["REQUEST_METHOD"] == "GET"){
    $stmt = myPDO::getInstance()->prepare(<<<SQL
      SELECT *
      FROM Fichier
      WHERE idFichier = :idFichier
SQL
);
    $stmt->execute(array(':idFichier' => secureInput($_GET['idFichier'])));
    //$stmt->setFetchMode(PDO::FETCH_CLASS,'Fichier');
    $res = $stmt->fetch();

    echo json_encode($res);
  }
}

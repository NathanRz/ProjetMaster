<?php

include_once("autoload.include.php");
require_once "mypdo.include.php";
require_once "utils.php";

if(Admin::isConnected()){
  if($_SERVER["REQUEST_METHOD"] == "POST"){
    Groupe::addGroup($_POST['idMod'], $_POST['lib'], $_POST['type'], $_POST['horaire']);
  }
  
  $stmt = myPDO::getInstance()->prepare(<<<SQL

    SELECT * FROM groupe WHERE idModule = :id

SQL
);

  $stmt->execute(array("id" => secureInput($_POST['idMod'])));

  $res = $stmt->fetchAll();

  echo json_encode($res);
}

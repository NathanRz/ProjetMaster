<?php

include_once("autoload.include.php");
require_once "mypdo.include.php";
require_once "utils.php";

if(Admin::isConnected()){
  if($_SERVER["REQUEST_METHOD"] == "POST"){
    $stmt = myPDO::getInstance()->prepare(<<<
      INSERT INTO groupe VALUES(null, :idMod, :lib, :type)
);
    $stmt->execute(array(":idMod" => secureInput($_POST['idMod']),
                         ":lib" => secureInput($_POST['lib']),
                         ":type" => secureInput($_POST['type'])));
  }
    $res = $stmt->fetchAll();
  echo json_encode($res);
}

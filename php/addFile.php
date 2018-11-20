<?php
include_once("autoload.include.php");
require_once "mypdo.include.php";
require_once "utils.php";

if(Admin::isConnected()){
	if($_SERVER["REQUEST_METHOD"] == "POST"){

    $mod = Module::getModuleById($_POST["idModule"]);
    switch ($_POST["typeFile"]){
      case "CM":
        $folder = "CM/";
        break;
      case "TD":
        $folder = "TD/";
        break;
      case "TP":
        $folder ="TP/";
        break;
    }

    $target_dir = "../docs/" . $mod->getLibModule() . "/" . $folder;
    $target_file = $target_dir . basename($_FILES["cmFile"]["name"]);
    $path = "docs/" . $mod->getLibModule() ."/" . $folder  . $_FILES["cmFile"]["name"];

    if(move_uploaded_file($_FILES["cmFile"]["tmp_name"], $target_file)){
      $stmt = myPDO::getInstance()->prepare(<<<SQL
        INSERT INTO fichier
        VALUES(null,:idModule, :nomFichier, :descFichier, :typeFichier, :cheminFichier);
SQL
  );

      $stmt->execute(array(':idModule' => $_POST["idModule"],
                        ':nomFichier' => $_FILES["cmFile"]["name"],
                        ':descFichier' => $_POST["descFile"],
                        ':typeFichier' => $_POST["typeFile"],
                        ':cheminFichier' => $path));
      }
  }
}


//header("Location: ../module.php");

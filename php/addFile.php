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
    $target_file = $target_dir . basename($_FILES["addedFile"]["name"]);
    $path = "docs/" . $mod->getLibModule() ."/" . $folder  . $_FILES["addedFile"]["name"];

    if(move_uploaded_file($_FILES["addedFile"]["tmp_name"], $target_file)){
      $stmt = myPDO::getInstance()->prepare(<<<SQL
        INSERT INTO fichier
        VALUES(null,:idModule, :nomFichier, :descFichier, :typeFichier, :cheminFichier);
SQL
  );

      $stmt->execute(array(':idModule' => $_POST["idModule"],
                        ':nomFichier' => $_FILES["addedFile"]["name"],
                        ':descFichier' => $_POST["descFile"],
                        ':typeFichier' => $_POST["typeFile"],
                        ':cheminFichier' => $path));
      }
  }
}


header("Location: ../module.php?id={$_POST["idModule"]}");

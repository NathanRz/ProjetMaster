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

		if($_FILES['imgDesc']['size'] != 0){
			$target_imgDesc = $target_dir . basename($_FILES["imgDesc"]["name"]);
			$pathImg = "docs/" . $mod->getLibModule() ."/" . $folder  . $_FILES["imgDesc"]["name"];

			if(move_uploaded_file($_FILES["addedFile"]["tmp_name"], $target_file) && move_uploaded_file($_FILES["imgDesc"]["tmp_name"], $target_imgDesc)){
				$stmt = myPDO::getInstance()->prepare(<<<SQL
					INSERT INTO fichier
					VALUES(null,:idModule, :nomFichier, :descFichier, :typeFichier, :cheminFichier, :cheminImg);
SQL
		);

				$stmt->execute(array(':idModule' => $_POST["idModule"],
													':nomFichier' => $_FILES["addedFile"]["name"],
													':descFichier' => $_POST["descFile"],
													':typeFichier' => $_POST["typeFile"],
													':cheminFichier' => $path,
													':cheminImg' => $pathImg));
				}

		}else{
			if(move_uploaded_file($_FILES["addedFile"]["tmp_name"], $target_file)){
				$stmt = myPDO::getInstance()->prepare(<<<SQL
					INSERT INTO fichier
					VALUES(null,:idModule, :nomFichier, :descFichier, :typeFichier, :cheminFichier, NULL);
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
}


header("Location: ../module.php?id={$_POST["idModule"]}");

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
    $target_file = $target_dir . basename($_FILES["file"]["name"]);
    $path = "docs/" . $mod->getLibModule() ."/" . $folder  . $_FILES["file"]["name"];

		if(array_key_exists('fileImg', $_FILES)){
			$target_fileImg = $target_dir . basename($_FILES["fileImg"]["name"]);
			$pathImg = "docs/" . $mod->getLibModule() ."/" . $folder  . $_FILES["fileImg"]["name"];

			if(move_uploaded_file($_FILES["file"]["tmp_name"], $target_file) && move_uploaded_file($_FILES["fileImg"]["tmp_name"], $target_fileImg)){
	      $stmt = myPDO::getInstance()->prepare(<<<SQL
	        INSERT INTO fichier
	        VALUES(null,:idModule, :nomFichier, :descFichier, :typeFichier, :cheminFichier, :cheminImg);
SQL
	  );

	      $stmt->execute(array(':idModule' => $_POST["idModule"],
	                        ':nomFichier' => $_FILES["file"]["name"],
	                        ':descFichier' => $_POST["descFile"],
	                        ':typeFichier' => $_POST["typeFile"],
	                        ':cheminFichier' => $path,
													':cheminImg' => $pathImg));
	      }

		}else{
			if(move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)){
				$stmt = myPDO::getInstance()->prepare(<<<SQL
					INSERT INTO fichier
					VALUES(null,:idModule, :nomFichier, :descFichier, :typeFichier, :cheminFichier, NULL);
SQL
		);

				$stmt->execute(array(':idModule' => $_POST["idModule"],
													':nomFichier' => $_FILES["file"]["name"],
													':descFichier' => $_POST["descFile"],
													':typeFichier' => $_POST["typeFile"],
													':cheminFichier' => $path));
			}
		}
  }

	$stmt = myPDO::getInstance()->prepare(<<<SQL
		SELECT *
		FROM fichier
		WHERE idModule = :idMod
SQL
	);
	$stmt->execute(array(':idMod' => $_POST["idModule"]));
	$res = $stmt->fetchAll();

	echo json_encode($res);
}

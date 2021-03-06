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

		$pathImg=NULL;
		$target_imgDesc = NULL;

		$pathSrc=NULL;
		$target_srcFile = NULL;

		if($_FILES["imgDesc"]["tmp_name"] != NULL){
				$extImage = substr($_FILES["imgDesc"]["name"],strpos($_FILES["imgDesc"]["name"],"."));
				$target_imgDesc = $target_dir . substr($_FILES["file"]["name"], 0, strrpos($_FILES["file"]["name"], ".")). "_Image" . $extImage;
		}

		if(array_key_exists('srcFile',$_FILES))
				$target_srcFile = $target_dir . substr($_FILES["file"]["name"], 0, strrpos($_FILES["file"]["name"], ".")). "_Sources.zip";

		if(move_uploaded_file($_FILES["imgDesc"]["tmp_name"], $target_imgDesc))
			$pathImg = "docs/" . $mod->getLibModule() ."/" . $folder  . substr($_FILES["file"]["name"], 0, strrpos($_FILES["file"]["name"], ".")). "_Image" . $extImage;

		if(array_key_exists('srcFile',$_FILES) && move_uploaded_file($_FILES["srcFile"]["tmp_name"], $target_srcFile))
			$pathSrc = "docs/" . $mod->getLibModule() ."/" . $folder  .substr($_FILES["file"]["name"], 0, strrpos($_FILES["file"]["name"], ".")). "_Sources.zip";

			if(move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)){
	      $stmt = myPDO::getInstance()->prepare(<<<SQL
	        INSERT INTO fichier
	        VALUES(null,:idModule, :nomFichier, :descFichier, :typeFichier, :cheminFichier, :cheminImg, :cheminSource);
SQL
	  );

	      $stmt->execute(array(':idModule' => $_POST["idModule"],
	                        ':nomFichier' => $_FILES["file"]["name"],
	                        ':descFichier' => $_POST["descFile"],
	                        ':typeFichier' => $_POST["typeFile"],
	                        ':cheminFichier' => $path,
													':cheminImg' => $pathImg,
													':cheminSource' => $pathSrc));
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
}

<?php
include_once("autoload.include.php");
require_once "mypdo.include.php";
require_once "utils.php";

$uploadOk = true;
if(Admin::isConnected()){
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		$dir=null;
		if(isset($_FILES['img']) && !empty($_FILES['img']["tmp_name"])){
			$target_dir = "../img/modImg/";
			$dir = "img/modImg/" . basename($_FILES["img"]["name"]);
			$name = basename($_FILES["img"]["name"]);
			$target_file = $target_dir . basename($_FILES["img"]["name"]);


			$check = getimagesize($_FILES["img"]["tmp_name"]);

			if($check == false)
				$uploadOk = false;

			if($uploadOk && file_exists($target_file))
				$uploadOk = false;

			if($uploadOk && $_FILES["img"]["size"] > 5000000)
				$uploadOk = false;

			move_uploaded_file($_FILES["img"]["tmp_name"], $target_file);

		}

		$dirNewFold = "../docs/" . secureInput($_POST['lib']) . "/";
		if($dir === null){
			$stmt = myPDO::getInstance()->prepare(<<<SQL
				INSERT INTO module VALUES(null,:lib,:descrip,null,:pass,:startDate,:endDate,:duree)
SQL
	);

			$stmt->execute(array(':lib' 	=> secureInput($_POST['lib']),
						 ':descrip' => secureInput($_POST['desc']),
						 ':pass' 	=> secureInput($_POST['mdp']),
						 ':startDate' => secureInput($_POST['startDate']),
						 ':endDate' => secureInput($_POST['endDate']),
						 ':duree' => secureInput($_POST['duree'])));
		}else{
			$stmt = myPDO::getInstance()->prepare(<<<SQL
				INSERT INTO module VALUES(null,:lib,:descrip,:img,:pass,:startDate,:endDate,:duree)
SQL
	);

			$stmt->execute(array(':lib' 	=> secureInput($_POST['lib']),
						 ':descrip' => secureInput($_POST['desc']),
						 ':img' => secureInput($dir),
						 ':pass' 	=> secureInput($_POST['mdp']),
						 ':startDate' => secureInput($_POST['startDate']),
						 ':endDate' => secureInput($_POST['endDate']),
						 ':duree' => secureInput($_POST['duree'])));
		}



		if(mkdir($dirNewFold)){
			mkdir($dirNewFold . "TD/");
			mkdir($dirNewFold . "TP/");
			mkdir($dirNewFold . "CM/");
			mkdir($dirNewFold . "Projets/Images");
			mkdir($dirNewFold . "Projets/Rapports");
			mkdir($dirNewFold . "Projets/Sources");
		}

	}

}

header("Location: ../enseignements.php");

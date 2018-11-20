<?php
include_once("autoload.include.php");
require_once "mypdo.include.php";
require_once "utils.php";

$uploadOk = true;
if(Admin::isConnected()){
	if($_SERVER["REQUEST_METHOD"] == "POST"){

		$target_dir = "../img/modImg/";
		$dir = "img/modImg/" . basename($_FILES["img"]["name"]);
		$name = basename($_FILES["img"]["name"]);
		$target_file = $target_dir . basename($_FILES["img"]["name"]);
		$dirNewFold = "../docs/" . secureInput($_POST['lib']) . "/";

		$check = getimagesize($_FILES["img"]["tmp_name"]);

		if($check == false)
			$uploadOk = false;

		if($uploadOk && file_exists($target_file))
			$uploadOk = false;
		
		if($uploadOk && $_FILES["img"]["size"] > 5000000)
			$uploadOk = false;

		if(move_uploaded_file($_FILES["img"]["tmp_name"], $target_file)){
			$stmt = myPDO::getInstance()->prepare(<<<SQL
				INSERT INTO module VALUES(null,null,:lib,:descrip,:img,:pass)
SQL
);

			$stmt->execute(array(':lib' 	=> secureInput($_POST['lib']),
							 ':descrip' => secureInput($_POST['desc']),
							 ':img' => secureInput($dir),
							 ':pass' 	=> secureInput($_POST['mdp'])));

			if(mkdir($dirNewFold)){
				mkdir($dirNewFold . "TD/");
				mkdir($dirNewFold . "TP/");
				mkdir($dirNewFold . "CM/");
			}
			
		}else{

		}

		
		
	}


}


header("Location: ../enseignements.php");
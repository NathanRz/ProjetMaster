<?php
include_once("autoload.include.php");
require_once "mypdo.include.php";
require_once "utils.php";

if(Admin::isConnected()){
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		
			$img = addslashes(file_get_contents($_FILES["image"]["tmp_name"]));

			$stmt = myPDO::getInstance()->prepare(<<<SQL
				INSERT INTO module VALUES(null,null,:lib,:descrip,:img,:pass)
SQL
);

			$stmt->execute(array(':lib' 	=> secureInput($_POST['lib']),
							 ':descrip' => secureInput($_POST['desc']),
							 ':img' => $img,
							 ':pass' 	=> secureInput($_POST['mdp'])));

		
	}


}


header("Location: ../enseignements.php");
<?php

include_once("autoload.include.php");
require_once "mypdo.include.php";
require_once "utils.php";

if(Admin::isConnected()){
	if($_SERVER["REQUEST_METHOD"] == "GET"){
		$stmt = myPDO::getInstance()->prepare(<<<SQL
			DELETE FROM module WHERE idModule = :idMod
SQL
);

		$stmt->execute(array(':idMod' 	=> secureInput($_GET['idMod'])));

	}
}


header("Location: ../enseignements.php");
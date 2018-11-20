<?php

include_once "autoload.include.php";
require_once "mypdo.include.php";
require_once "utils.php";

if(Admin::isConnected()){
	if($_SERVER["REQUEST_METHOD"] == "POST"){

		$id = secureInput($_POST['idMod']);
		$idInt = (int)$id;
		$module = Module::getModuleById($idInt);

		if($module->getImgMod() !== null){
			
			unlink("../" . $module->getImgMod());
			deleteDirectory("../docs/". $module->getLibModule());

				$stmt = myPDO::getInstance()->prepare(<<<SQL
				DELETE FROM module WHERE idModule = :idMod
SQL
);
				$stmt->execute(array(':idMod' => $id));

		}else{
		
			$stmt = myPDO::getInstance()->prepare(<<<SQL
				DELETE FROM module WHERE idModule = :idMod
SQL
);
				$stmt->execute(array(':idMod' => $id));
		}

		

	}
}


header("Location: ../enseignements.php");
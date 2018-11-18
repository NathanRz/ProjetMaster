<?php
include_once("autoload.include.php");
require_once "mypdo.include.php";
require_once "utils.php";


if(isset($_POST) && !empty($_POST)){
	$stmt = myPDO::getInstance()->prepare(<<<SQL
		INSERT INTO module VALUES(null,null,:lib,:descrip,:pass)
SQL
);

	$stmt->execute(array(':lib' 	=> secureInput($_POST['lib']),
						 ':descrip' => secureInput($_POST['desc']),
						 ':pass' 	=> secureInput($_POST['mdp'])));

}

header("Location: ../enseignements.php");